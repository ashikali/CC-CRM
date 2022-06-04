<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyContactRequest;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('contact_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $contacts = Contact::all();

        return view('admin.contacts.index', compact('contacts'));
    }

    public function create()
    {
        abort_if(Gate::denies('contact_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.contacts.create');

    }

    public function store(StoreContactRequest $request)
    {
        $data = $request->all();
        $data["cedula_ruc"] = "XX";
        $data["apellido"] = "XX";  // last name
        $data["name"] = "XX";
        $contact = Contact::create($data);
        return redirect()->route('admin.contacts.index');
    }

    public function edit(Contact $contact)
    {
        abort_if(Gate::denies('contact_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.contacts.edit', compact('contact'));

    }

    public function update(UpdateContactRequest $request, Contact $contact)
    {

        $data = $request->all();
        $data["cedula_ruc"] = "XX";
        $data["apellido"] = "XX";  // last name
        $data["name"] = "XX";
        $contact->update($data);
        return redirect()->route('admin.contacts.index');
    }

    public function destroy(Contact $contact)
    {
        abort_if(Gate::denies('contact_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $contact->delete();

        return back();
    }

    public function massDestroy(MassDestroyContactRequest $request)
    {
        Contact::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
