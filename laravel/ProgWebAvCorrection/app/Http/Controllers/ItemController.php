<?php namespace App\Http\Controllers;

use Request;
use Illuminate\Contracts\Auth\Guard;
use App\Item;

class ItemController extends Controller {

        private $auth;

        /**
        * @param \Illuminate\Contracts\Auth\Guard  $auth
        */
        public function __construct(Guard $auth)
        {
            $this->auth = $auth;
            $this->middleware('aclRest');
        }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
            if ($this->auth->user()->hasRole('admin')) {
                $items = Item::all();
            } else {
                $items = $this->auth->user()->items;
            }
            return response($items->toJson(), 200, array('Content-Type' => 'application/json'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
            $title = Request::input('title');
            // validation des inputs (le titre de l'item)
            if (!Item::isValid(['title' => $title])) {
                return response('validation failed', 400);
            }
            $item = new Item();
            $item->title = $title;
            $this->auth->user()->items()->save($item);
            return response($item->toJson(), 201, array('Content-Type' => 'application/json'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
            // Validation des inputs
            if (!Item::isValid(['id' => $id])) {
                return response('not found', 404);
            }
            // Verification que l'item appartient bien au user
            $item = $this->auth->user()->items()->find($id);
            if (!isset($item)) {
                return response('forbidden', 403);
            }
            return response($item->toJson(), 200, array('Content-Type' => 'application/json'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
            $title = Request::input('title');
            if (!Item::isValid(['title' => $title, 'id' => $id])) {
                return response('validation failed', 400);
            }
            // Verification que l'item appartient bien au user
            $item = $this->auth->user()->items()->find($id);
            if (!isset($item)) {
                return response('forbidden', 403);
            }
             // Modification du titre de l'item
            $item->title = $title;
            $item->save();
            return response($item->toJson(), 200, array('Content-Type' => 'application/json'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
	    // Validation des inputs
            if (!Item::isValid(['id' => $id])) {
                return response('not found', 404);
            }
            // Verification que l'item appartient bien au user
            $item = $this->auth->user()->items()->find($id);
            if (!isset($item)) {
                return response('forbidden', 403);
            }
            // Suppression de l'item
            $item->delete();
	    return response(null, 204);
	}

}
