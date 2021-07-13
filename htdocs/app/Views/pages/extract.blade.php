@extends('general_layout')

@section('head')
    <title>Extrato</title>
@endsection

@section('navbaritems')
    <!-- Button to open the modal login form -->
    <li><a href="/logout">Logout</a></li>
@endsection

@section('saldo')
    <div class="saldo">
        Saldo R${{$balance}}
    </div>
@endsection

@section('header')
@endsection

@section('main')
    <h1 class="extracth1">Extrato</h1>
    <div class="tableScroll">
        <table class="styled-table">
            <thead>
            <tr>
                <th>Conta Origem</th>
                <th>Conta Destino</th>
                <th>Tipo de Transação</th>
                <th>Valor</th>
                <th>Saldo</th>
                <th>Data</th>
            </tr>
            </thead>
            <tbody>
            @foreach($extract as $transaction)
                <tr>
                    <td>{{$transaction->from_acc}}</td>
                    <td>{{$transaction->to_acc}}</td>
                    <td>{{$transaction->type}}</td>
                    <td>{{$transaction->amount}}</td>
                    <td>{{$transaction->balance}}</td>
                    <td>{{$transaction->created_at}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection