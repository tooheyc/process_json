var sortOrder = {
    column: 'LName',
    order: 'desc',
    type: '',
    json: ''
};

// Change highlighting when a table heading or button is clicked.
function setSelection(id, others) {
    for (var i in others) {
        if (others[i] != id) {
            $("#" + others[i]).attr('class', 'notSelected');
        }
    }
    $("#" + id).attr('class', 'selected');
}

// Set new direction and call server for a new sort on the field and direction.
function setDir(id) {
    var opposite = {
        asc: 'desc',
        desc: 'asc'
    };
    sortOrder.order = id;
    sortOrder.type = '';
    setSelection(id, opposite);
    sort(sortOrder.column);
}

// Sort based on currently selected column and direction.
function sort(id) {
    var tableName = 'sortable';
    var columns = {
        Age: 'Age',
        FName: 'FName',
        LName: 'LName'
    };
    if (id.substr(-3) == "Too") {
        const too = 'Too';
        tableName += too;
        columns.Age += too;
        columns.FName += too;
        columns.LName += too;
        sortOrder.type = 'Too';
    } else {
        sortOrder.type = '';
    }
    setSelection(id, columns);
    sortOrder.column = id;
    postNewSort(tableName);
}

// Update table row after receiving response from server.
function setRow(data, rowNum) {
    var tableName = 'sortable' + sortOrder.type;
    var x = document.getElementById(tableName).rows[rowNum].cells;
    for (var j in data) {
        x[j].innerHTML = data[j];
    }
}

// Process server response.
function showResponse(resp) {
    var responseObj = JSON.parse(resp);
    for (var i in responseObj) {
        setRow(responseObj[i], parseInt(i) + 1);
    }
}

// Ajax call to server.
function postNewSort(id) {
    getData(id);
    $.ajax({
        type: "POST",
        url: './ajax',
        data: sortOrder,
        success: showResponse
    });
}

// After switching to a new data source we don't need to parse it a second time.
// Instead, we'll just use the data already in the table.
function getData(tableName) {
    var x = document.getElementById(tableName).rows;
    var data = [];
    for (var i in x) {
        if (i == 0 || typeof (x[i].innerHTML) == "undefined") continue;
        var y = {};
        y.FName = x[i].cells[0].innerHTML;
        y.LName = x[i].cells[1].innerHTML;
        y.Age = x[i].cells[2].innerHTML;
        if(x[i].cells.length > 3){
            y.Errs = x[i].cells[2].innerHTML;
        }
        data.push(y);
    }
    sortOrder.json = JSON.stringify(data);
}

function setSource() {
    // set current sort column and order before submitting form.
    $("#column").val(sortOrder.column);
    $("#order").val(sortOrder.order);
    $("#setSource").submit();
}
