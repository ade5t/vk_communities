function logout() {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/../main.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var data = xhr.responseText;
            document.location.href = data;
        }
    };
    xhr.send('logout');
}

function login() {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/../main.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var data = xhr.responseText;
                document.location.href = data;
        }
    };
    xhr.send('login');
}

function get_communities() {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/../main.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Заполняем таблицу.
            var data = JSON.parse(xhr.responseText);
            data.forEach(function (item, i, data) {
                var th0 = document.createElement("th");
                var td1 = document.createElement("td");
                // var td2 = document.createElement("td");
                th0.innerHTML = i;
                td1.innerHTML = item["name"];
                // td2.innerHTML = item["date"];
                var row = document.createElement("tr");
                row.appendChild(th0);
                row.appendChild(td1);
                // row.appendChild(td2);
                var tab = document.getElementById('tab').getElementsByTagName('tbody')[0];
                tab.appendChild(row);
            })
            // Выводим формы с фильтрами вместо прелоадера.
            var filter = document.getElementById('filter');
            filter.removeAttribute("align");
            filter.innerHTML =
                '   <form role="form" class="form-inline">\n' +
                '        <div class="form-group row">\n' +
                '            <div class="input-group" id="datetimepicker2">\n' +
                '                <input type="text" class="form-control" name="dte" oninput="input_datetimepicker2();" placeholder="Дата последнего поста"/>\n' +
                '                <span class="input-group-addon">\n' +
                '                    <i class="glyphicon glyphicon-calendar"></i>\n' +
                '                </span>\n' +
                '            </div>\n' +
                '            <div id="subscribers" class="input-group">\n' +
                '                <input type="text" class="form-control" id="int" min="1" oninput="input_subscribers();" placeholder="Кол-во подписчиков"/>\n' +
                '            </div>\n' +
                '            <div class="input-group">\n' +
                '                <button type="button" name="btn" class="btn btn-primary" onclick="submit_btn();">Принять</button>\n' +
                '            </div>\n' +
                '        </div>\n' +
                '    </form>\n' +
                '<br>';
                // Инициализируем календарь для ввода даты
                $(function () {
                    $('#datetimepicker2').datetimepicker({
                        locale: 'ru',
                        format: 'YYYY-MM-DD',
                        showClear: true
                    });
                });
        }
    };
    xhr.send('communities');
}

function submit_btn() {
    var body = 'date=' + encodeURIComponent($('#datetimepicker2').data('date')) + '&sub=' + encodeURIComponent($('#int').val());
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/../main.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var data = JSON.parse(xhr.responseText);
            switch (data) {
                case 'invalid_sub':
                    $("#subscribers").addClass("has-error");
                    break;
                case 'invalid_date':
                    $("#datetimepicker2").addClass("has-error");
                    break;
                default:
                    // Очищаем таблицу
                    for(var i = document.getElementById('tab').rows.length - 1; i > 0; i--)
                    {
                        document.getElementById('tab').deleteRow(i);
                    }
                    // Заполняем таблицу.
                    JSON.parse(xhr.responseText).forEach(function (item, i, data) {
                        var th0 = document.createElement("th");
                        var td1 = document.createElement("td");
                        // var td2 = document.createElement("td");
                        th0.innerHTML = i;
                        td1.innerHTML = item["name"];
                        // td2.innerHTML = item["date"];
                        var row = document.createElement("tr");
                        row.appendChild(th0);
                        row.appendChild(td1);
                        // row.appendChild(td2);
                        var tab = document.getElementById('tab').getElementsByTagName('tbody')[0];
                        tab.appendChild(row);
                    })
            }
        }
    };
    xhr.send(body);
}

function input_subscribers() {
    $("#subscribers").removeClass("has-error");
}

function input_datetimepicker2() {
    $("#datetimepicker2").removeClass("has-error");
}