<?php

namespace App\Http\Controllers\Api;

use App\Models\Link;
use App\Transformers\LinkTransformer;
use Illuminate\Http\Request;

class LinksController extends Controller
{
    public function index()
    {
        $links = (new Link())->getAllCached();

        return $this->response->collection($links, new LinkTransformer());
    }
}
