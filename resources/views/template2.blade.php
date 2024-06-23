<!DOCTYPE html>
<html lang="">
<head>
    <style>

        @media screen {
        }

        body {
            background-image: url('http://localhost:8000/img/template.png');
            background-repeat: no-repeat;

            background-size: 210mm 297mm; /* A4 dimensions: width = 210mm, height = 297mm */
            background-position: center center;
        }

        @media print {
            body {
                background-image: url('http://localhost:8000/img/template.png') !important;
                -webkit-print-color-adjust: exact !important;
                background-repeat: no-repeat !important;
                background-size: cover !important;
                background-position: center center !important;
                width: 210mm !important;
                height: 297mm !important;
            }

            .avoid-break {
                page-break-inside: avoid;
            }
        }

        /* Define the A4 page size for printing */
        @page {
            size: A4;
            margin: 0;
        }

        /* Set the body size to match the A4 dimensions */
        body {
            /*width: 210mm;*/
            height: 297mm;
        }


        .mydata {
            display: flex;
            flex-wrap: wrap;
            align-content: space-around;
            justify-content: space-around;
            flex-direction: column;
            align-items: center;
        }

        table {
            width: 200% !important;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 9px !important;
            display: flex;
            flex-wrap: wrap;
            align-content: space-around;
            justify-content: space-around;
            flex-direction: column;
            align-items: center;
        }

        table {
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black !important;
            padding: 8px;
            text-align: center;
        }


    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <title>Order</title>
</head>
<body>
<!-- Content goes here -->
<div class="mydata">
    <div class="">
        <span>+998 71 202 22 28</span>
        <span>ул. Мусамухамедова, дом 21</span></div>

    <table>
        <tr>
            <td style="width: 20%">Авиабилет</td>
            <td style="width: 40%">-</td>
            <td style="width: 40%">Название:</td>
        </tr>
        <tr>
            <td>Отель</td>
            <td>-</td>
            <td>Ok</td>
        </tr>
        <tr>
            <td>Трансфер</td>
            <td>-</td>
            <td>Ok</td>
        </tr>
        <tr>
            <td>Виза</td>
            <td>-</td>
            <td>Ok</td>
        </tr>
        <tr>
            <td>Страхование</td>
            <td>-</td>
            <td>Ok</td>
        </tr>
        <tr>
            <td>Количествотуристов:</td>
            <td>1 взрослых</td>
            <td>Ok</td>
        </tr>
        <tr>
            <td>Стоимостьтура</td>
            <td>977 500 сўм</td>
            <td>Ok</td>
        </tr>
        <tr>
            <td>Оплачено</td>
            <td>977 500 сўм</td>
            <td>Ok</td>
        </tr>
        <tr>
            <td>Остаток:</td>
            <td>0 сўм</td>
            <td>Ok</td>
        </tr>
        <tr>
            <td>Комментарии:</td>
            <td>Перевод на карту</td>
            <td>Ok</td>
        </tr>
        <tr>
            <td>Заказчик</td>
            <td>ERGASHEV XUSNIDDIN</td>
            <td>+998 98 110 99 94</td>
        </tr>
        <tr>
            <td>Оператор</td>
            <td>Шахрух</td>
            <td>95 115 22 28</td>
        </tr>
        <tr>
            <td>Услугафирмы:</td>
            <td>0</td>
            <td></td>
        </tr>
    </table>
    <div class="">
        <span>+998 71 202 22 28</span>
        <span>ул. Мусамухамедова, дом 21</span></div>

    <table>
        <tr>
            <td style="width: 20%">Авиабилет</td>
            <td style="width: 40%">-</td>
            <td style="width: 40%">Название:</td>
        </tr>
        <tr>
            <td>Отель</td>
            <td>-</td>
            <td>Ok</td>
        </tr>
        <tr>
            <td>Трансфер</td>
            <td>-</td>
            <td>Ok</td>
        </tr>
        <tr>
            <td>Виза</td>
            <td>-</td>
            <td>Ok</td>
        </tr>
        <tr>
            <td>Страхование</td>
            <td>-</td>
            <td>Ok</td>
        </tr>
        <tr>
            <td>Количествотуристов:</td>
            <td>1 взрослых</td>
            <td>Ok</td>
        </tr>
        <tr>
            <td>Стоимостьтура</td>
            <td>977 500 сўм</td>
            <td>Ok</td>
        </tr>
        <tr>
            <td>Оплачено</td>
            <td>977 500 сўм</td>
            <td>Ok</td>
        </tr>
        <tr>
            <td>Остаток:</td>
            <td>0 сўм</td>
            <td>Ok</td>
        </tr>
        <tr>
            <td>Комментарии:</td>
            <td>Перевод на карту</td>
            <td>Ok</td>
        </tr>
        <tr>
            <td>Заказчик</td>
            <td>ERGASHEV XUSNIDDIN</td>
            <td>+998 98 110 99 94</td>
        </tr>
        <tr>
            <td>Оператор</td>
            <td>Шахрух</td>
            <td>95 115 22 28</td>
        </tr>
        <tr>
            <td>Услугафирмы:</td>
            <td>0</td>
            <td></td>
        </tr>
    </table>
    <hr>

</div>


</body>
</html>
