<?php

namespace App\Http\Controllers;
use \App\pbbj;
use \App\unitkerja;
use \App\pegawai;
use \App\pengadaan;
use \App\prosespengadaan;
use Alert;
use DB;
use \App\barang;

use Illuminate\Http\Request;

class PenugasanController extends Controller
{
    public function receivePpbj() {
        $data['receiveallPpbj'] = pbbj::paginate(10);
        $data['prosespengadaan'] = prosespengadaan::get();
        $data['unitkerja'] = unitkerja::get();
        return view('kasubag.all')->with($data);
    }

    //kalau data di prosespengadaan belum ada pemekerja, akan menjalankan method ini dan melanjutkan ke method saveAssignment
    public function addAsignment($id) {
       $data['ppbjassignmentEdit'] = pbbj::find($id);
        $data['unitkerja'] = unitkerja::get();
        $data['pegawai'] = pegawai::get();
        $data['pengadaan'] = pengadaan::get();
        $data['prosespengadaan'] = prosespengadaan::all();
        $data['jumlah'] = barang::where('id', '=', $id)->count();
        $data['barang'] = barang::find($id);
        $data['barangnya'] = barang::where('id', '=', $id)->get();
        $data['id'] = $id;
        // $data['unit'] = \App\unitkerja::where('id_unit', $id)->first();
        return view('kasubag.add')->with($data);
    }
    //lanjutan function dari addAsignment
    public function saveAssignment(Request $r) {
        $prosespengadaan = new prosespengadaan;
         $newproses = pbbj::find($r->input('id'));
         $prosespengadaan->id_pegawai = $r->input('id_pegawai');
        if($r->input('p_tglspph') == ""){
            $prosespengadaan->tgl_spph = "Belum Terselesaikan";
        }else{
            $prosespengadaan->tgl_spph = $r->input('p_tglspph');
        }
        if($r->input('p_nospph') == "") {
            $prosespengadaan->no_spph = "Belum Terselesaikan";
        }else{
            $prosespengadaan->no_spph = $r->input('p_nospph');
            $prosespengadaan->selesaispph = date('Y-m-d H:i:s');
        }
        $prosespengadaan->id = $newproses->id;
        $prosespengadaan->save();
        $newproses->id_pegawai = $prosespengadaan->id_pegawai;
        $newproses->save();
        Alert::success('Data Ppbj telah ditugaskan.', 'Berhasil!')->autoclose(1300);
        return redirect()->route('receivePpbj');
    }

    //kalau data di prosespengadaan sudah ada pemekerja, akan menjalankan method ini dan melanjutkan ke method updateassignmentPpbj
    public function editassignmentPpbj($id, ...$id_prosespengadaan) 
    {
    	$data['ppbjassignmentEdit'] = pbbj::find($id);
        $data['unitkerja'] = unitkerja::get();
        $data['pegawai'] = pegawai::get();
        $data['pengadaan'] = pengadaan::get();
        $data['prosespengadaan'] = prosespengadaan::find($id_prosespengadaan);
        $data['jumlah'] = barang::where('id', '=', $id)->count();
        $data['barang'] = barang::find($id);
        $data['barangnya'] = barang::where('id', '=', $id)->get();
        $data['id'] = $id;
        // $data['unit'] = \App\unitkerja::where('id_unit', $id)->first();
        return view('kasubag.edit')->with($data);
    }

    public function updateassignmentPpbj(Request $r, ...$id)
    {
        $newprosespengadaan = new prosespengadaan;
        // $editprosespengadaan = prosespengadaan::find($r->input('id_pemroses'));

        $newproses = pbbj::find($r->input('id'));
        $newprosespengadaan->id_pegawai = $r->input('id_pegawai');
        if($r->input('p_tglspph') == ""){
            $newprosespengadaan->tgl_spph = "Belum Terselesaikan";
        }else{
            $newprosespengadaan->tgl_spph = $r->input('p_tglspph');
        }
        if($r->input('p_nospph') == "") {
            $newprosespengadaan->no_spph = "Belum Terselesaikan";
        }else{
            $newprosespengadaan->no_spph = $r->input('p_nospph');
            $newprosespengadaan->selesaispph = date('Y-m-d H:i:s');
        }

        if($r->input('p_tgletp') == "" ) {
            $newprosespengadaan->tgl_etp = "Belum Terselesaikan";
        }else{
            $newprosespengadaan->tgl_etp = $r->input('p_tgletp');
            $newprosespengadaan->selesaietp = date('Y-m-d H:i:s');
        }

        if($r->input('p_tglpmn') == "" ) {
            $newprosespengadaan->tgl_pmn = "Belum Terselesaikan";
        }else{
            $newprosespengadaan->tgl_pmn = date($r->input('p_tglpmn'));
        }if($r->input('p_nopmn') == "") {
            $newprosespengadaan->no_pmn = "Belum Terselesaikan";
        }
        else{
            $newprosespengadaan->no_pmn = $r->input('p_nopmn');
            $newprosespengadaan->selesaipmn = date('Y-m-d H:i:s');
        }

        if($r->input('p_tglkon') == "") {
            $newprosespengadaan->tgl_kon = "Belum Terselesaikan";
        }else{
            $newprosespengadaan->tgl_kon = date($r->input('p_tglkon'));
        }if($r->input('p_nokon') == "") {
            $newprosespengadaan->no_kon = "Belum Terselesaikan";
        }else{
            $newprosespengadaan->no_kon = $r->input('p_nokon');
            $newprosespengadaan->selesaikon = date('Y-m-d H:i:s');
        }
        $newprosespengadaan->id = $newproses->id; //id prosespengadaan == id ppbj 
        $newprosespengadaan->save();



        $data = $r->except(['_token']); 
      // return dd($data);

        $row = count($data['id_barang']);
        for ($i=0; $i < $row; $i++) {        
            DB::table('barangs')->where('id_barang', '=', $data['id_barang'][$i])->update([
              'banyak_brg' => $r->input('row'),
              'nama_barang' => $data['nama'][$data['id_barang'][$i]],
              'jumlah_brg' => $data['qty'][$data['id_barang'][$i]],
              'harga_brg' => $data['harga'][$data['id_barang'][$i]],
              'total_brg' => $data['total'][$data['id_barang'][$i]],
              'hargatotal_brg' => $r->input('subtotal')]);
        }

        
        $newproses->id_pegawai = $newprosespengadaan->id_pegawai;
        $newproses->save();
        Alert::success('Data Ppbj telah ditugaskan22', 'Berhasil!')->autoclose(1300);
        return redirect()->route('receivePpbj');

    }
}
