<?php

namespace Emrdev\SfBugBundle\Controller;


use CURLFile;
use Symfony\Bundle\WebProfilerBundle\Profiler\TemplateManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;


class SfbugController
{

    private $baseDir;
    private $twig;
    private $templateManager;
    private $templates;
    private $profiler;

    public function __construct(string $baseDir = null, Environment $twig,  $templates, Profiler $profiler = null)
    {
        $this->baseDir = $baseDir;
        $this->twig = $twig;
        $this->templates = $templates;
        $this->profiler = $profiler;
    }



    public function getFormView($token){
        $response = new Response();
        if ('latest' === $token && $latest = current($this->profiler->find(null, null, 1, null, null, null))) {
            $token = $latest['token'];
        }
        $profile = $this->profiler->loadProfile($token);
        if(!$profile){
            return  $response->setContent($this->twig->render('@WebProfiler/SfBug/not_found.html.twig', ['token' => $token]));
        }
        $this->templateManager = new TemplateManager($this->profiler, $this->twig, $this->templates);
        return  $response->setContent($this->twig->render('@WebProfiler/SfBug/form.html.twig', ['profile' => $profile, 'templates' => $this->templateManager->getNames($profile), 'profiler_markup_version' => 2]));
    }

    /**
     * @Route("/_profiler/share", name="sfbug_share", methods={"POST"})
     */
    public function shareAction(Request $request)
    {
        $key = $request->get('token');
        if ('latest' === $key && $latest = current($this->profiler->find(null, null, 1, null, null, null))) {
            $key = $latest['token'];
        }
        if (empty($key) or strlen($key) != 6 or !$this->profiler->loadProfile($key)) {
            return (new Response())->setStatusCode(404, 'Token invalid or not found');
        }

        $panels = $request->get('panels');
        if (empty($panels)) {
            return (new Response())->setStatusCode(404, 'You must select at least one page');
        }

        $cache_file = $this->baseDir . '\\var\\cache\\dev\\profiler';
        $array_key = array_reverse(str_split($key, 2));
        $array_key = array_slice($array_key, 0, 2);
        foreach ($array_key as $item) {
            $cache_file .= "\\" . $item;
        }
        $cache_file .= '\\' . $key;
        if (!is_file($cache_file)) {
                return (new Response())->setStatusCode(404, "Can't find file ($cache_file)");
        }

        if (!is_readable($cache_file)) {
            return (new Response())->setStatusCode(403, "File not readable ($cache_file)");
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sfbug.io/transport',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => ['file'=> new CURLFILE($cache_file),'token' => $key, 'panels' => json_encode(array_keys($panels))],
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response,true);

        if(!empty($response['error'])){
            return (new Response())->setStatusCode(400, $response['error']);
        }elseif(empty($response['link'])){
            return (new Response())->setStatusCode(400, "Something went wrong. Failed to generate link, please try again later or make sure you have an internet connection");
        }
        return new JsonResponse($response);
    }
}
