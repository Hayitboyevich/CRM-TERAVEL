<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .qrcode {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        body {
            margin: 0;
            background-image: url("{{url('/user-uploads/check_theme'). '/'.company()->check_theme}}");
            background-repeat: no-repeat;
            background-size: cover;
        }

        @media print {
            .body {
                background-image: url("{{url('/user-uploads/check_theme'). '/'.company()->check_theme}}");
            }
        }

        .top-title {
            padding: 0px 20px;
        }

        .div {
            margin-top: 250px;
            margin-bottom: 200px;
        }

        .invoice-table th, .invoice-table td {
            padding: 0.2rem 0.75rem;

        }


        table {
            text-align: center;
        }

        .dotted-hr {
            border: none;
            border-top: 3px dashed #000;

            margin: 22px auto;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="div">
        <div class="invoice">
            <div class="top-title">
                <div class="row" style="display: flex; justify-content: space-between;">
                    <h5 class="heading-space">{{company()->company_name}}</h5>
                    <h5>Номер заказа #{{$order->id}}-{{$order->application_id}}</h5>
                    <h5></h5>
                </div>
                <div class="row">
                    <p class="heading-space">{{company()->company_phone}}</p>
                    <p style="margin-left: auto;">{{company()->companyAddress()->first()->address}}</p>
                </div>
            </div>

            <table class="table table-bordered invoice-table">
                <thead>
                <tr>
                    <th scope="col">Авиабилет</th>
                    <td scope="col">{{$services["air_ticket"] ? '+' : '-'}}</td>
                    <th scope="col">Название:</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">Отель</th>
                    <td>{{$services["hotel"] ? '+' : '-'}}</td>
                    <td rowspan="4">{{$order->name}}</td>
                </tr>
                <tr>
                    <th scope="row">Трансфер</th>
                    <td>{{$services["transfer"] ? '+' : '-'}}</td>
                </tr>
                <tr>
                    <th scope="row">Виза</th>
                    <td>{{$services["visa"] ? '+' : '-'}}</td>
                </tr>
                <tr>
                    <th scope="row">Страхование</th>
                    <td>{{$services["insurance"] ? '+' : '-'}}</td>
                </tr>
                <tr>
                    <th>Количество туристов:</th>
                    <td>{{$people['adults']>0 ? $people['adults'] : ''}} {{$people['adults'] > 0 ? "взрослых" : ""}} {{$people['children']>0 ? $people['children'] : ''}} {{$people['children'] > 0 ? "детей" : ""}}</td>
                    <td>Дата: {{date('d.m.Y', strtotime($order->created_at))}}</td>
                </tr>
                <tr>
                    <th>Стоимость тура:</th>
                    <td>{{currency_format($price_all,company()->currency_id )}}</td>
                    <td rowspan="4">
                        <div class="qrcode" id="qrcode1"></div>
                    </td>
                </tr>
                <tr>
                    <th>Оплачено:</th>
                    <td>{{$price_paid }}</td>
                </tr>
                <tr>
                    <th>Остаток:</th>
                    <td>{{ $price_left  }}</td>
                </tr>
                <tr>
                    <th>Комментарии:</th>
                    <td>{{$comment}}</td>
                </tr>
                <tr>
                    <th>Заказчик:</th>
                    <td>{{($client_name)}}</td>
                    <td>{{$client_mobile}}</td>
                </tr>
                <tr>
                    <th>Оператор:</th>
                    <td>{{($operator_name)}}</td>
                    <td>{{$operator_mobile}}</td>
                </tr>
                <tr>
                    <th>Услуга фирмы:</th>
                    <td>0 UZS</td>
                    <td></td>
                </tr>
                </tbody>
            </table>
            <hr class="dotted-hr">
            <div class="top-title">
                <div class="row" style="display: flex; justify-content: space-between;">
                    <h5 class="heading-space">{{company()->company_name}}</h5>
                    <h5>Номер заказа #{{$order->id}}</h5>
                    <h5></h5>
                </div>
                <div class="row">
                    <p class="heading-space">{{company()->company_phone}}</p>
                    <p style="margin-left: auto;">{{company()->companyAddress()->first()->address}}</p>
                </div>
            </div>
            <table class="table table-bordered invoice-table">
                <thead>
                <tr>
                    <th scope="col">Авиабилет</th>
                    <td scope="col">{{$services["air_ticket"] ? '+' : '-'}}</td>
                    <th scope="col">Название:</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">Отель</th>
                    <td>{{$services["hotel"] ? '+' : '-'}}</td>
                    <td rowspan="4">{{$order->name}}

                    </td>
                </tr>
                <tr>
                    <th scope="row">Трансфер</th>
                    <td>{{$services["transfer"] ? '+' : '-'}}</td>

                </tr>
                <tr>
                    <th scope="row">Виза</th>
                    <td>{{$services["visa"] ? '+' : '-'}}</td>
                </tr>
                <tr>
                    <th scope="row">Страхование</th>
                    <td>{{$services["insurance"] ? '+' : '-'}}</td>
                </tr>
                <tr>
                    <th>Количество туристов:</th>
                    <td>{{$people['adults']>0 ? $people['adults'] : ''}} {{$people['adults'] > 0 ? "взрослых" : ""}} {{$people['children']>0 ? $people['children'] : ''}} {{$people['children'] > 0 ? "детей" : ""}}</td>
                    <td>Дата: {{date('d.m.Y', strtotime($order->created_at))}}</td>
                </tr>
                <tr>
                    <th>Стоимость тура:</th>
                    <td>{{currency_format($price_all,$order->currency_id )}}</td>
                    <td rowspan="4">
                        <div class="qrcode" id="qrcode"></div>
                    </td>
                </tr>
                <tr>
                    <th>Оплачено:</th>
                    <td>{{$price_paid}}</td>
                </tr>
                <tr>
                    <th>Остаток:</th>
                    <td>{{$price_left}}</td>
                </tr>
                <tr>
                    <th>Комментарии:</th>
                    <td>{{$comment}}</td>
                </tr>
                <tr>
                    <th>Заказчик:</th>
                    <td>{{strtoupper($client_name)}}</td>
                    <td>{{$client_mobile}}</td>
                </tr>
                <tr>
                    <th>Оператор:</th>
                    <td>{{strtoupper($operator_name)}}</td>
                    <td>{{$operator_mobile}}</td>
                </tr>
                <tr>
                    <th>Услуга фирмы:</th>
                    <td>0 UZS</td>
                    <td></td>
                </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const size = 80;
        const url = "{{route('payments.custom.create', $order->id)}}";
        new QRCode(document.getElementById("qrcode"), {
            text: url,
            width: size,
            height: size
        });
        new QRCode(document.getElementById("qrcode1"), {
            text: url,
            width: size,
            height: size
        });
    });
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
