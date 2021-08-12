<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use App\Models\Produk;
use App\Models\Belanja;
use Livewire\Component;

class Home extends Component
{
    public $products = [];
    public $search, $min, $max;

    public function beli($id)
    {
        if(!Auth::user())
        {
            return Redirect()->route('login');
        }

        $produk = Produk::find($id);

        Belanja::create(
            [
                'user_id'   => Auth::user()->id,
                'produk_id' => $produk->id,
                'total_harga' => $produk->harga,
                'status'    => 0
            ]
        );
        return redirect()->to('BelanjaUser');
    }

    public function render()
    {
        if($this->max)
        {
            $harga_max = $this->max;
        }
        else
        {
            $harga_max = 50000000;
        }

        if($this->min)
        {
            $harga_min = $this->min;
        }
        else
        {
            $harga_min = 0;
        }


        if($this->search)
        {
            $this->products = Produk::where('nama','like','%'.$this->search.'%')
                ->where('harga', '>=', $harga_min)
                ->where('harga', '<=', $harga_max)
                ->get();
        }
        else
        {
            $this->products = Produk::where('harga', '>=', $harga_min)
                                    ->where('harga', '<=', $harga_max)
                                    ->get();
        }

        return view('livewire.home')->extends('layouts.app')->section('content');
    }
}
