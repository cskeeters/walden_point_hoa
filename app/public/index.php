<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Twig\Extra\String\StringExtension;
use Psr\Container\ContainerInterface;

require __DIR__ . '/../vendor/autoload.php';

//With default settings
/* $settings = require __DIR__ . '/../src/settings.php'; */
/* $app = new \Slim\App($settings); */
$app = AppFactory::create();

$twig = Twig::create('../view', ['cache' => false]);

$app->add(TwigMiddleware::create($app, $twig));

/**
 * The routing middleware should be added earlier than the ErrorMiddleware
 * Otherwise exceptions thrown from it will not be handled by the middleware
 */
$app->addRoutingMiddleware();

/**
 * Add Error Middleware
 *
 * @param bool                  $displayErrorDetails -> Should be set to false in production
 * @param bool                  $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool                  $logErrorDetails -> Display error details in error log
 * @param LoggerInterface|null  $logger -> Optional PSR-3 Logger
 *
 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$errorMiddleware = $app->addErrorMiddleware(true, true, true);


//With custom settings
/* $app = new Slim\App(array( */
/*     'log.enable' => true, */
/*     'log.path' => './logs', */
/*     'log.level' => 4, */
/*     'view' => 'MyCustomViewClassName' */
/* )); */

$app->get('/', function (Request $request, Response $response, array $args) {
    /* $response->getBody()->write("Hello, world"); */
    $response = Twig::fromRequest($request)->render($response, 'index.twig', []);
    return $response;
});

$app->get('/doc/{name}', function (Request $request, Response $response, array $args) {
    $terms = [];
    $terms['fee'] = 'an estate of land, especially one held on condition of feudal service';
    $terms['lender'] = 'the bank or lender that is using the lot as collateral';

    try {
        $name = $args['name'];
        $response = Twig::fromRequest($request)->render($response, "doc/$name.twig", ['terms' => $terms]);
        return $response;
    }
    catch (Exception $e) {
        $response = $responseFactory->createResponse(404);
        return $response;
        //code to handle the exception
    }
});

$app->run();
?>
