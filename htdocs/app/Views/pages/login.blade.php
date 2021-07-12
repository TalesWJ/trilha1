@extends('general_layout')

@section('head')
    <title>Login</title>
@endsection

@section('navbaritems')
    <!-- Button to open the modal login form -->
    <li><a onclick="document.getElementById('id01').style.display='block'" class="aHome">Cadastre-se</a></li>
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

    <!-- The Modal -->
    <div id="id01" class="modal">
      <span onclick="document.getElementById('id01').style.display='none'"
            class="close" title="Close Modal">&times;</span>

        <!-- Modal Content -->
        <form class="modal-content animate" action="registerPost" method="post">
            <div class="imgcontainer">
                <img src="../../../public/Misc/Images/img_avatar2.jpg" alt="Avatar" class="avatar">
            </div>

            <div class="containerLogin">
                <section class="sectionlogin">
                    <center><h2 class="form-title">Informações de Cadastro</h2></center>
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

                <label for="rg" class="labelLogin"><b>Registro Geral*</b></label>
                <br>
                <input type="text" placeholder="Ex.: 00.000.000" name="rg" id="rg"
                       minlength="10" maxlength="10" required><br>

                <label for="dob" class="labelLogin"><b>Data de Nascimento*</b></label>
                <br>
                <input type="date" name="dob" id="dob" class="dob" required><br>

                <label for="phone" class="labelLogin"><b>Telefone*</b></label>
                <br>
                <input type="text" placeholder="Ex.: (00)0000-0000" name="phone" id="phone" required><br>

                <label for="password" class="labelLogin"><b>Senha*</b></label>
                <br>
                <input type="password" placeholder="Senha" name="password" id="password"
                       minlength="5" required>
                <br>

                <section class="sectionlogin">
                    <center><h2 class="form-title">Informações de Endereço</h2></center>
                </section class="sectionlogin">
                <br>

                <label for="CEP" class="labelLogin"><b>CEP*</b></label><br>
                <input type="text" placeholder="Ex.: 00000-000" name="zipcode" id="zipcode" required>

                <label for="country" class="labelLogin"><b>País*</b></label><br>
                <input type="text" placeholder="" name="country" id="country" required>

                <label for="state" class="labelLogin"><b>Estado*</b></label><br>
                <input type="text" placeholder="" name="state" id="state" required>

                <label for="city" class="labelLogin"><b>Cidade*</b></label><br>
                <input type="text" placeholder="" name="city" id="city" required>

                <label for="street" class="labelLogin"><b>Rua/Logradouro*</b></label><br>
                <input type="text" placeholder="" name="street" id="street" required>

                <label for="number" class="labelLogin"><b>Número*</b></label><br>
                <input type="text" placeholder="" name="number" id="number" required>

                <label for="complement" class="labelLogin"><b>Complemento</b></label><br>
                <input type="text" placeholder="" name="complement" id="complement">

                <button type="submit" class="loginbtn">Cadastrar</button>
            </div>

            <div class="containerLogin">
                <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
            </div>
        </form>
    </div>

    <div class="content">
        <img src="../../../public/Misc/Images/coin.png" class="feature-img">
        <h1>WJCRYPTO</h1>
        <p>A cripto-moeda do futuro!</p>
    </div>

    <form class="login" action="loginPost" method="post">
        <div class="imgcontainer">
            <img src="../../../public/Misc/Images/img_avatar2.jpg" alt="Avatar" class="avatar">
        </div>

        <div class="containerLogin">
            <section class="sectionlogin">
                <center><h2 class="form-title">Informações de usuário</h2></center>
            </section class="sectionlogin">
            <br>
            <label for="acc_number" class="labelLogin"><b>Conta</b></label>
            <br>
            <input type="text" placeholder="Número da Conta" name="acc_number" id="acc_number" required>
            <br>
            <label for="password" class="labelLogin"><b>Senha</b></label>
            <br>
            <input type="password" placeholder="Senha" name="password" id="password" required>

            <button type="submit" class="loginbtn">Login</button>
        </div>
    </form>

    <script>
        // Get the modal
        var modal = document.getElementById('id01');

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
@endsection