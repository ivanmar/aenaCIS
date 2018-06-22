<?php

namespace App\Http\Controllers;

use \DB, \Session, \PDF;
use Illuminate\Http\Request;

class StornoController extends Controller {

    var $input_rules = ['dateIssue' => 'required', 'nrInvoice' => 'required | integer | min:1', 'years' => 'required | integer | min:1'];

    public function __construct(Request $request) {
        $this->middleware('auth');
        $this->request = $request;
    }

    private function insertMainSql($id = 0) {
        $nrInvoice = $this->request->input('nrInvoice');
        $year = $this->request->input('years');
        $idInvoiceOut = DB::table('invoiceOut')->where('nrInvoice', $nrInvoice)->where('dateIssue', '>=', $year . '-0-0')->value('id');
        if (isset($idInvoiceOut) && $idInvoiceOut > 0) {
            if ($id > 0) {
                $storno = \App\Storno::find($id);
            } else {
                $storno = new \App\Storno;
            }
            $storno->dateIssue = $this->request->input('dateIssue');
            $storno->nrStorno = ($id == 0) ? DB::table('storno')->max('nrStorno') + 1 : $this->request->input('nrStorno');
            $storno->idInvoiceOut = $idInvoiceOut;
            $storno->desc = $this->request->input('desc');
            $storno->save();

            DB::table('invoiceOut')->where('id', $storno->idInvoiceOut)->update(array('indStorno' => 1));
            return $storno->id;
        } else {
            return false;
        }
    }

    public function index() {
        $storno = \App\Storno::with('invoiceout')->get();
        return view('storno.index')
                        ->with('actInvo', 'active')
                        ->with('obj', $storno);
    }

    public function create() {

        return view('storno.form')
                        ->with('formAction', 'storno.store')
                        ->with('formMethod', 'POST')
                        ->with('years', ovrGetYearList())
                        ->with('year', 0)
                        ->with('nrInvoice', '')
                        ->with('actInvo', 'active')
                        ->with('obj', new \App\Storno);
    }

    public function store() {
        $this->validate($this->request, $this->input_rules);
        if ($this->insertMainSql()) {
            Session::flash('message', 'Successfully created');
        } else {
            Session::flash('message-err', 'ERROR: Could not find Invoice number');
        }
        return redirect('storno');
    }

    public function edit($id) {

        $storno = \App\Storno::with('invoiceout')->find($id);

        return view('storno.form')
                        ->with('formAction', 'storno.update')
                        ->with('formMethod', 'PUT')
                        ->with('years', ovrGetYearList())
                        ->with('year', date('Y'), strtotime($storno->invoiceout->dateIssue))
                        ->with('nrInvoice', $storno->invoiceout->nrInvoice)
                        ->with('actInvo', 'active')
                        ->with('indEdit', true)
                        ->with('obj', $storno);
    }

    public function update($id) {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql($id);
        Session::flash('message', 'Successfully updated');
        return redirect('storno');
    }

    public function show($id) {
        $storno = \App\Storno::find($id);
        $invoiceout = \App\InvoiceOut::find($storno->idInvoiceOut);
        $company = \App\Company::find($invoiceout->idCompany);
        if ($company) {
            $cName = $company->name;
            $cAddress = $company->address;
            $cDdv = $company->ddvCode;
            $cZip = $company->zipCode;
            $cCity = $company->city;
        } else {
            $cName = 'končni kupec';
            $cAddress = '';
            $cDdv = '';
            $cZip = '';
            $cCity = '';
        }
        $items = DB::table('invoiceOutArt')->where('idInvoiceOut', $invoiceout->id)->get();
        $data = array(
            'company' => $cName, 'nrStorno' => $storno->nrStorno, 'nrInvoice' => $invoiceout->nrInvoice,
            'dateIssue' => $storno->dateIssue, 'dateIssueInv' => $invoiceout->dateIssue,
            'address' => $cAddress, 'ddvCode' => $cDdv, 'zipCode' => $cZip, 'city' => $cCity,
            'desc' => $storno->desc, 'items' => $items
        );
        $pdf = PDF::loadView('pdfgen.invoiceStorno0', $data);
        return $pdf->stream($storno->nrStorno . '-dobropis.pdf');
    }

    public function destroy($id) {

        DB::transaction(function() use ($id) {
            $storno = \App\Storno::find($id);
            DB::table('invoiceOut')->where('id', $storno->idInvoiceOut)->update(array('indStorno' => 0));
            DB::table('storno')->where('id', $id)->delete();
        });

        Session::flash('message', 'Successfully deleted');
        return redirect('storno');
    }

}
