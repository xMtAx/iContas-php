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
            @if (!$expenses)
                <h2 class="text-center">Sem despesas</h2>
            @endif
            @foreach ($expenses as $expense)
                <a class="expense_link {{ $expense->paid ? 'border-green' : ($expense->due_date < date('Y-m-d') ? 'border-red' : '' ) }}" href="/expenses/edit/{{ $expense->id }}">
                    <div class="expense_details">
                        <span class="expense_title">{{ $expense->title }}</span>
                        <span class="expense_text">{{ $expense->due_date->format('d/m/Y') }}</span>
                    </div>
                    <div class="expense_details">
                        <span class="expense_title">R$ {{ number_format($expense->value, 2, ',', '.') }}</span>
                        @if ($expense->paid)
                            <span class="expense_text">pago</span>
                        @elseif ($expense->due_date < date('Y-m-d'))
                            <span class="expense_text">vencido</span>
                        @else
                            <span>&nbsp;</span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endsection
