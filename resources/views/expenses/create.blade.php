@extends('layouts.app')

@section('content')
    <!--::header part start::-->
    <header class="main_menu home_menu">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light align-items-center justify-content-between pt-3">
                <a class="navbar-brand" href="/dashboard"> <img src="{{ asset('img/landing/logo.png') }}" width="34"
                        alt="logo"> iConta$
                </a>

                <div style="display: flex; gap: 10px;">
                    <a class="btn_2 d-lg-block" href="/expenses/create">Criar despesa</a>
                    <a class="btn_2 bg-red" href="/logout" style="padding: 16px;"><i class="ti-power-off"></i></a>
                </div>
            </nav>
        </div>
    </header>
    <!-- Header part end-->

    <!-- content part start-->
    <section class="content_part">
        <div class="expense_container">
            <form action="/expenses" method="post">
                @csrf
                <div class="mb-3">
                    <label for="title">Título</label>
                    <input type="title" class="form-control" name="title" placeholder="Aluguel">
                </div>
                <div class="mb-3">
                    <label for="value">Valor</label>
                    <input type="number" class="form-control" name="value" placeholder="500.00" step=".01">
                </div>
                <div class="mb-4">
                    <label for="due_date">Vencimento</label>
                    <input type="date" class="form-control" name="due_date">
                </div>
                <button type="submit" class="btn_2 mb-3" style="border: none; width: 100%;">Enviar</button>
            </form>
        </div>
    </section>
@endsection
