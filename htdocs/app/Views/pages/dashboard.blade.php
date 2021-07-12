@extends('general_layout')

@section('head')
    <title>Login</title>
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

@if (isset($message))
@section('alert')
    <div class="alert">
        <span class="btnAlert" onclick="this.parentElement.style.display='none';">&times;</span>
        {{ $message }} @if (isset($acc_number)) Número da conta: {{ $acc_number }} @endif
    </div>
@endsection
@endif

@section('header')
@endsection

@section('main')
    <div class="divDash">
        <ul>
            <div>
                <img src="../../../public/Misc/Images/transaction.png" class="transacIcon">
                <li><a onclick="document.getElementById('id01').style.display='block'">Transferência</a></li><br>
            </div>

            <img src="../../../public/Misc/Images/deposit.png" class="transacIcon">
            <li><a onclick="document.getElementById('id02').style.display='block'">Depósito</a></li><br>
            <img src="../../../public/Misc/Images/money-withdrawal.png" class="transacIcon">
            <li><a onclick="document.getElementById('id03').style.display='block'">Saque</a></li><br>
            <img src="../../../public/Misc/Images/search.png" class="transacIcon">
            <li><a onclick="document.getElementById('id04').style.display='block'">Extrato</a></li>
        </ul>
    </div>

    <div id="id01" class="modal">
      <span onclick="document.getElementById('id01').style.display='none'"
            class="close" title="Close Modal">&times;</span>

        <!-- Modal Content -->
        <form class="modal-content animate" action="transferPost" method="post">
            <div class="imgcontainer">
                <img src="../../../public/Misc/Images/transaction.png" alt="Avatar" class="avatar">
            </div>

            <div class="containerLogin">
                <section class="sectionlogin">
                    <center><h2 class="form-title">Transferência</h2></center>
                </section>
                <br>

                <label for="to_acc" class="labelLogin"><b>Conta Destino*</b></label>
                <br>
                <input type="text" placeholder="Número da conta" name="to_acc" id="to_acc" required>
                <br>

                <label for="amount" class="labelLogin"><b>Valor à ser transferido*</b></label>
                <br>
                <input type="text" placeholder="Ex.: 400.00" name="amount" id="amount" required> <br>

                <button type="submit" class="loginbtn">Transferir</button>
            </div>

            <div class="containerLogin">
                <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
            </div>
        </form>
    </div>

    <div id="id02" class="modal">
      <span onclick="document.getElementById('id02').style.display='none'"
            class="close" title="Close Modal">&times;</span>

        <!-- Modal Content -->
        <form class="modal-content animate" action="depositPost" method="post">
            <div class="imgcontainer">
                <img src="../../../public/Misc/Images/deposit.png" alt="Avatar" class="avatar">
            </div>

            <div class="containerLogin">
                <section class="sectionlogin">
                    <center><h2 class="form-title">Depósito</h2></center>
                </section>
                <br>

                <label for="amount" class="labelLogin"><b>Valor à ser depositado*</b></label>
                <br>
                <input type="text" placeholder="Ex.: 400.00" name="amount" id="amount" required> <br>

                <button type="submit" class="loginbtn">Depositar</button>
            </div>

            <div class="containerLogin">
                <button type="button" onclick="document.getElementById('id02').style.display='none'" class="cancelbtn">Cancel</button>
            </div>
        </form>
    </div>

    <div id="id03" class="modal">
      <span onclick="document.getElementById('id03').style.display='none'"
            class="close" title="Close Modal">&times;</span>

        <!-- Modal Content -->
        <form class="modal-content animate" action="withdrawPost" method="post">
            <div class="imgcontainer">
                <img src="../../../public/Misc/Images/money-withdrawal.png" alt="Avatar" class="avatar">
            </div>

            <div class="containerLogin">
                <section class="sectionlogin">
                    <center><h2 class="form-title">Saque</h2></center>
                </section>
                <br>

                <label for="amount" class="labelLogin"><b>Valor à ser sacado*</b></label>
                <br>
                <input type="text" placeholder="Ex.: 400.00" name="amount" id="amount" required> <br>

                <button type="submit" class="loginbtn">Sacar</button>
            </div>

            <div class="containerLogin">
                <button type="button" onclick="document.getElementById('id03').style.display='none'" class="cancelbtn">Cancel</button>
            </div>
        </form>
    </div>

    <div id="id04" class="modal">
      <span onclick="document.getElementById('id04').style.display='none'"
            class="close" title="Close Modal">&times;</span>

        <!-- Modal Content -->
        <form class="modal-content animate" action="extractPost" method="post">
            <div class="imgcontainer">
                <img src="../../../public/Misc/Images/img_avatar2.jpg" alt="Avatar" class="avatar">
            </div>

            <div class="containerLogin">
                <section class="sectionlogin">
                    <center><h2 class="form-title">Extrato</h2></center>
                </section>
                <br>

                <label for="name" class="labelLogin"><b>Nome*</b></label>
                <br>
                <input type="text" placeholder="Nome Completo" name="name" id="name" required>
                <br>

                <label for="cpf" class="labelLogin"><b>CPF*</b></label>
                <br>
                <input type="text" placeholder="Ex.: 000.000.000-00" name="cpf" id="cpf"
                       minlength="14" maxlength="14" required> <br>

                <button type="submit" class="loginbtn">Confirmar</button>
            </div>

            <div class="containerLogin">
                <button type="button" onclick="document.getElementById('id04').style.display='none'" class="cancelbtn">Cancel</button>
            </div>
        </form>
    </div>

@endsection