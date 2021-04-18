<?php


namespace App\Controller;


use Laminas\Code\Annotation\Parser\DoctrineAnnotationParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ApidocController extends AbstractController
{
    /**
     * @Route("/apidoc")
     */
    public function index()
    {
        $reflection = new \ReflectionClass(AdvisorController::class);
        $classDoc = $this->parsePhpDoc($reflection->getDocComment());
        $reflection->getDocComment();
        $parser = new DoctrineAnnotationParser();
        $methods = [];
        /** @var \ReflectionMethod $method */
        foreach ($reflection->getMethods() as $method) {
            $doc = $this->parsePhpDoc($method->getDocComment());
            if (!$doc || is_null($doc['route'])) {
                continue;
            }
            $methods['('.$doc['method'].') - '. $classDoc['route'].$doc['route']] = $doc['description'];
        }
        return $this->render('/apidoc.twig', [
            'classDoc' => $this->parsePhpDoc($reflection->getDocComment())['description'],
            'methodDocs' => $methods]);
    }

    private function parsePhpDoc(string $doc): array
    {
        $return = [];
        preg_match('/\* @Route\("([^"]*)",/',$doc,$route);
        $return['route'] = isset($route[1]) ? $route[1] : null;
        preg_match('/\* @Route\("[^"]*",.*methods={"([A-Z]*)"}/',$doc,$route);
        $return['method'] = $route[1] ?? 'GET';
        $doc = substr($doc, 0, strpos($doc,'* @'));
        $doc = preg_replace('/ \* /', '', $doc);
        $doc = preg_replace('/^ {2,}/', ' ', $doc);
        $doc = preg_replace('#/\*\* *\r\n#', '', $doc);
        $return['description'] = $doc ?? null;
        return $return;
    }

}