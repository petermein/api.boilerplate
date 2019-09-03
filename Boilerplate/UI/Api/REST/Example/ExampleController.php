<?php declare(strict_types=1);

namespace Boilerplate\UI\Api\REST\Example;

use App\Http\Controllers\Controller;

class ExampleController extends Controller
{
    public function getAll(){
        //validate incoming request

        //Make the CQRS request

        //Pass request to bus or mediator

        //Return response

        $body = 'body';

        return response($body, $code = 200);
    }
}