<html>

<head>
    <script src="https://cdn.staticfile.org/jquery/2.0.3/jquery.min.js"></script>

</head>
<style>
    table,
    th,
    td {
        border: 1px solid black;
        border-collapse: collapse;
    }
</style>


<body>

    <div>
        <label>Date: </label>
        <input type="text" id="fdate" placeholder="xxxx-xx-xx">
        <button onclick="submit()">submit</button>
    </div>

    <div>
        <label>Search: </label>
        <input type="text" id="fteam">
        <button onclick="submit()">搜尋</button>
    </div>
    <div id="detial">
        <table>
            <tbody id="rows">
            </tbody>
        </table>
    </div>

</body>
<script>
    function submit() {

        var url = "http://localhost/demo/apiFetch.php?date="
        var get = {}
        get['date'] = document.getElementById('fdate').value;
        get['team'] = document.getElementById('fteam').value;

        console.log(get)
        for (key in get) {

            // &key=value
            url += '&' + key + "=" + get[key]
        }


        $.ajax({
            url: url,
            success: function(response) {
                if (response.success) {
                    show(response.payload)
                } else {
                    $('#rows').html("")
                }

            }
        })

    }

    function show(payload) {

        var html = `
            <tr>
                <th>Number</th>
                <th>Competition</th>
                <th>Time</th>
                <th>Away team</th>
                <th>Home team</th>
                <th>Count</th>
            </tr>
        `
        payload.forEach(row => {
            console.log(row)

            rowCountHtml = colorTag([row['lose'], row['win']])

            html += `
            <tr>
                <td>` + row['no'] + `</td>
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
        console.log(count)
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


    // function search() {

    //     var team = document.getElementById('fteam').value;
    //     var removeIdx = []


    //     $("tr").each(function(index) {
    //         if (index > 0) {
    //             console.log(index)
    //             var find = false
    //             $(this).find("td").each(function() {
    //                 if ($(this).text().match(team)) {
    //                     find = true
    //                 }
    //             })
    //             if (!find) {
    //                 // $('tr:eq(' + index + ')').remove()
    //                 removeIdx.push(index);
    //             }
    //         }


    //     })

    //     removeIdx.reverse().forEach(idx => {
    //         $('tr:eq(' + idx + ')').remove()
    //     })

    //     // console.log(team)
    // }

    // function search() {
    //     var date = document.getElementById('fdate').value

    //     $.ajax({
    //         url: "http://localhost/demo/apiFetch.php?action=search&date=" + date,
    //         success: function(response) {
    //             if (response.success) {
    //                 show(response.payload)
    //             } else {
    //                 $('#rows').html("")
    //             }

    //         }
    //     })
    // }
</script>

</html>