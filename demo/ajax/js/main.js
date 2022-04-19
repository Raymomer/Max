

function submit() {

    $('#rows').html("")
    $('.contest-spinner').css(
        'visibility', 'visible'
    )

    var url = "http://localhost/Max/demo/apiFetch.php?"
    var get = {}
    get['date'] = document.getElementById('fdate').value;
    get['team'] = document.getElementById('fteam').value;

    for (key in get) {

        // &key=value
        url += '&' + key + "=" + get[key]
    }


    $.ajax({
        url: url,
        success: function (response) {
            if (response.success) {
                show(response.payload)
            } else {
                $('#rows').html("<p>" + response.error_message + "</p>")
            }
            $('.contest-spinner').css(
                'visibility', 'collapse'
            )
        }
    })

}

function show(payload) {

    var html = `
    <thead>
        <tr>
            <th scope="col">#</th>
            <th>Competition</th>
            <th>Time</th>
            <th>Away team</th>
            <th>Home team</th>
            <th>Count</th>
        </tr>
    </thead>
    `


    payload.forEach(row => {

        rowCountHtml = colorTag([row['lose'], row['win']])

        html += `
        <tr>
            <th scope ="row">` + row['no'] + `</th>
            <td>` + row['type'] + `</td>
            <td>` + row['time'] + `</td>
            <td>` + row['away_team'] + `</td>
            <td>` + row['home_team'] + `</td>
            <td><div style="display:flex">` + rowCountHtml + `</div></td>
        </tr>
        `
    })

    $('#rows').html(html)
}

function colorTag(count) {

    html = ""
    count.forEach(res => {
        if (res > 2) {
            html += "<p style='color:red'>" + res + "&emsp;</p>"
        } else {
            html += "<p style='color:blue'>" + res + "&emsp;</p>"
        }
    })

    return html
}

