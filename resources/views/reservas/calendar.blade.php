@extends('adminlte::page')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Calendario</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container">
        <div class="calendar">
            <div class="month">
                <div class="prev" onclick="previousMonth()">&#10094;</div>
                <div class="month-title" id="month-title"></div>
                <div class="next" onclick="nextMonth()">&#10095;</div>
            </div>
            <ul class="weekdays">
                <li>Lunes</li>
                <li>Martes</li>
                <li>Miércoles</li>
                <li>Jueves</li>
                <li>Viernes</li>
                <li>Sábado</li>
                <li>Domingo</li>
            </ul>
            <ul class="days" id="days"></ul>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/calendar.css') }}">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f7f7;
            color: #333;
            margin: 0;
        }

        .calendar {
            width: 100%;
            max-width: 600px;
            background-color: #fff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            padding: 20px;
            margin: 20px auto 0; /* Moved up by 30 pixels */
        }

        .month {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #2c3e50;
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
        }

        .month .prev,
        .month .next {
            cursor: pointer;
            font-size: 24px;
        }

        .month-title {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            flex: 1;
        }

        .weekdays, .days {
            display: flex;
            flex-wrap: wrap;
            list-style-type: none;
            padding: 0;
            margin: 20px 0 0;
        }

        .weekdays li, .days li {
            width: 14.28%;
            text-align: center;
            padding: 10px 0;
            box-sizing: border-box;
        }

        .weekdays li {
            background-color: #ecf0f1;
            font-weight: bold;
        }

        .days li {
            background-color: #fff;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
            border: 1px solid transparent;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        .days li:hover {
            background-color: #bdc3c7;
            color: #fff;
        }

        .days li.selected {
            background-color: #2980b9;
            color: white;
            border: 1px solid #2980b9;
        }
    </style>
@stop

@section('js')
    <script>
        const monthTitleEl = document.getElementById('month-title');
        const daysEl = document.getElementById('days');

        let currentDate = new Date();

        function renderCalendar(date) {
            const month = date.getMonth();
            const year = date.getFullYear();

            let firstDay = new Date(year, month, 1).getDay();
            firstDay = firstDay === 0 ? 7 : firstDay; // Ajustar para que Lunes sea 1 y Domingo sea 7
            const lastDay = new Date(year, month + 1, 0).getDate();

            const months = [
                "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
            ];

            monthTitleEl.innerHTML = `${months[month]}<br><span style="font-size:18px">${year}</span>`;

            daysEl.innerHTML = '';

            for (let i = 1; i < firstDay; i++) {
                const emptyDay = document.createElement('li');
                daysEl.appendChild(emptyDay);
            }

            for (let i = 1; i <= lastDay; i++) {
                const day = document.createElement('li');
                day.textContent = i;
                day.onclick = () => selectDay(day);
                daysEl.appendChild(day);
            }
        }

        function selectDay(dayElement) {
            const selected = document.querySelector('.days .selected');
            if (selected) {
                selected.classList.remove('selected');
            }
            dayElement.classList.add('selected');
        }

        function previousMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar(currentDate);
        }

        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar(currentDate);
        }

        renderCalendar(currentDate);
    </script>
@stop
