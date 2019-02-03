window.chartColors = {
    red: 'rgb(255, 99, 132)',
    orange: 'rgb(255, 159, 64)',
    yellow: 'rgb(255, 205, 86)',
    green: 'rgb(75, 192, 192)',
    blue: 'rgb(54, 162, 235)',
    purple: 'rgb(153, 102, 255)',
    grey: 'rgb(201, 203, 207)'
};

$(document).ready(function () {

    function render(props) {
        return function (tok, i) { 
            return (i % 2) ? props[tok] : tok; };
    }

    $("#month-list-dropdown").on('load', function(e){
        var self = $(this),
            today = moment().date(1),
            monthList = [
                moment([moment().year(), moment().subtract(1, 'M').month(), 1]),
                moment([moment().year(), moment().month(), 1]),
                moment([moment().year(), moment().add(1, 'M').month(), 1])
            ]
        ;

        $.ajax({
            url: '/report/monthlist'
        })
            .done(function(data){
                monthList = [];
                $.each(data.options, function (key, value) {
                    monthList.push(moment(value));
                });
            })
            .always(function (data) {
                $.each(monthList, function (key, value) {
                    self.append(
                        $("<option></option>")
                            .prop("value", value.format("YYYY-MM-DD"))
                            .text(value.format("YYYY-MMMM"))
                    ).val(today.format("YYYY-MM-DD"));
                });
            })
        ;
        
    }).on('change', function(e){
        var currentDate = moment($(this).val());
        var href = "/report/calllog?month=" + currentDate.format("YYYY-MM-DD");
        window.location.href = href;
    }).trigger('load');

    $('#monthlistitem tbody').on('load', function(){
        var self = $(this),
            itemTpl = $('script[data-template="monthlistitem"]').text().split(/\$\{(.+?)\}/g)
        ;

        $.ajax({
            url: '/report/monthlistdata'
        })
            .done(function (data) {
                var defaultProps = {
                    "totalCall":0,
                    "totalCalled":0,
                    "totalNotCalled":0,
                    "YYYY-MMMM": moment().format("YYYY-MMMM"),
                    "YYYY-MM-DD": moment().format("YYYY-MM-DD")
                }
                var items = [];

                $.each(data, function (key, value) {
                    var props = $.extend({}, defaultProps, value);
                    props["YYYY-MMMM"] = moment(props.date).format('YYYY-MMMM');
                    props["YYYY-MM-DD"] = moment(props.date).format('YYYY-MM-DD');
                    items.push(props);
                });

                self.html('');
                self.append(items.map(function (item) {
                    return itemTpl.map(render(item)).join('');
                }));
            })
            ;


    }).trigger('load');

    $('#tblCustomerNoPhone tbody').on('load', function () {
        var self = $(this),
            itemTpl = $('script[data-template="customeritem"]').text().split(/\$\{(.+?)\}/g)
            ;

        $.ajax({
            url: '/report/customersnophone'
        })
            .done(function (data) {
                var defaultProps = {
                    "customerId": 0,
                    "lastName": "0",
                    "YYYY-MMMM": ''
                }
                var items = [];
                

                $.each(data.customers, function (key, value) {
                    var props = $.extend({}, defaultProps, value);
                    props["YYYY-MMMM"] = (props['YYYY-MMMM']).length ?  moment(props.date).format('YYYY-MMMM') : '';
                    items.push(props);
                });

                $("#nophone-total").text(data.counts);
                $("#nophone-count").text(data.customers.length);

                self.html('');
                self.append(items.map(function (item) {
                    return itemTpl.map(render(item)).join('');
                }));
            })
            ;


    }).trigger('load');

    $('#tblCustomerNoService tbody').on('load', function () {
        var self = $(this),
            itemTpl = $('script[data-template="customeritem"]').text().split(/\$\{(.+?)\}/g)
            ;

        $.ajax({
            url: '/report/customersnoservice'
        })
            .done(function (data) {
                var defaultProps = {
                    "customerId": 0,
                    "lastName": "0",
                    "YYYY-MMMM": ''
                }
                var items = [];


                $.each(data.customers, function (key, value) {
                    var props = $.extend({}, defaultProps, value);
                    props["YYYY-MMMM"] = (props['YYYY-MMMM']).length ? moment(props.date).format('YYYY-MMMM') : '';
                    items.push(props);
                });

                $("#noservice-total").text(data.counts);
                $("#noservice-count").text(data.customers.length);

                self.html('');
                self.append(items.map(function (item) {
                    return itemTpl.map(render(item)).join('');
                }));
            })
            ;


    }).trigger('load');

    $("#call-chart").on('load', function(e){
        var self = $(this);
        var MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        
        var config = {
            type: 'line',
            data:{
                labels: MONTHS
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Call Volume'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Month'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Call/Scheduled'
                        }
                    }]
                }
            }
        };

        var ctx = document.getElementById('myChart').getContext('2d');
        window.myLine = new Chart(ctx, config);

        var colorNames = Object.keys(window.chartColors);

        $.ajax({
            url: '/report/calldata'
        })
            .done(function (data) {

                $.each(data, function (key, value) {
                    var colorName = colorNames[config.data.datasets.length % colorNames.length];
                    var newColor = window.chartColors[colorName];

                    var newDataset = {
                        label: key,
                        backgroundColor: newColor,
                        borderColor: newColor,
                        data: [],
                        fill: false
                    };

                    $.each(MONTHS, function (index, name) {
                        newDataset.data.push(value[index + 1] ? value[index + 1] : 0);
                    });
                    config.data.datasets.push(newDataset);

                });
                window.myLine.update();
            })
            ;

    }).trigger('load');

    
    
});