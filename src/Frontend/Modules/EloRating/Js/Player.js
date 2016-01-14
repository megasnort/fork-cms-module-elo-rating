/**
 * Code for drawing a chart with the players ELO evolution
 *
 * @author  Stef Bastiaansen <stef@megasnort.com>
 */
jsFrontend.elo_rating =
{
    init: function () {
        jsFrontend.elo_rating.initGraph();
        jsFrontend.elo_rating.initOpponentSelect();
    },
    initOpponentSelect: function () {
        jsFrontend.elo_rating.showHideOpponents();
        $('#opponents').change(jsFrontend.elo_rating.showHideOpponents);
    },
    showHideOpponents: function () {
        var selected = $('#opponents').val();
        var rows = $("#games tr[data-id]");

        if (selected == '0') {
            rows.css('display', 'table-row');
        } else {
            rows.each(function () {
                console.log($(this).attr('data-id'));
                $(this).css('display', $(this).attr('data-id') == selected ? 'table-row' : 'none');
            });
        }

    },
    initGraph: function () {
        if (jsFrontend.data.exists('EloRating')) {
            var history = jsFrontend.data.get('EloRating.history');
            var dateFormat;

            history.forEach(function (d) {
                d.date = new Date(d.date);
            });

            if (history.length < 10) {
                dateFormat = "%d-%m-%y";
            } else {
                dateFormat = "%m-%y";
            }

            var vis = d3.select("#evolution");
            var width = $('#evolution').width();
            var height = 260;

            var margins = {
                top: 20,
                right: 30,
                bottom: 20,
                left: 40
            };

            var xRange = d3.time.scale().range([margins.left, width - margins.right]).domain([d3.min(history, function (d) {
                return d.date;
            }),
                d3.max(history, function (d) {
                    return d.date;
                })
            ]);

            var yRange = d3.scale.linear().range([height - margins.top, margins.bottom]).domain([d3.min(history, function (d) {
                return d.elo;
            }),
                d3.max(history, function (d) {
                    return d.elo;
                })
            ]);

            // draw grid, first, so it's in the back
            function make_x_axis() {
                return d3.svg.axis()
                    .scale(xRange)
                    .orient("bottom")
                    .ticks(10)
            }

            function make_y_axis() {
                return d3.svg.axis()
                    .scale(yRange)
                    .orient("left")
                    .ticks(5)
            }

            vis.append("svg:g")
                .attr("class", "grid")
                .attr("transform", "translate(0," + (height - margins.top) + ")")
                .call(make_x_axis()
                    .tickSize(-height + margins.bottom + margins.top, 0, 0)
                    .tickFormat("")
                );

            vis.append("svg:g")
                .attr("class", "grid")
                .attr("transform", "translate(" + (margins.left) + ", 0)")
                .call(make_y_axis()
                    .tickSize(-width + margins.left + margins.right, 0, 0)
                    .tickFormat("")
                );

            // DATES
            var xAxis = d3.svg.axis()
                .scale(xRange)
                .ticks(10)
                .tickFormat(d3.time.format(dateFormat))


            // ELO
            var yAxis = d3.svg.axis()
                .ticks(5)
                .scale(yRange)
                .tickSize(5)
                .orient("left")
                .tickFormat(d3.format('d'))


            //ELO
            vis.append("svg:g")
                .attr("class", "y axis")
                .attr("transform", "translate(" + (margins.left) + ",0)")
                .call(yAxis);

            // DATES
            vis.append("svg:g")
                .attr("class", "x axis")
                .attr("transform", "translate(0," + (height - margins.bottom) + ")")
                .call(xAxis)
                .selectAll("text")
                .style("text-anchor", "end")
                .attr("dx", "-.8em")
                .attr("dy", ".15em")
                .attr("transform", function (d) {
                    return "rotate(-65)"
                });

            // Draw the line
            var lineFunc = d3.svg.line()
                .x(function (d) {
                    return xRange(d.date);
                })
                .y(function (d) {
                    return yRange(d.elo);
                })
                .interpolate('basis');

            vis.append("svg:path")
                .attr("d", lineFunc(history))
                .attr("stroke", "#444")
                .attr("stroke-width", 1)
                .attr("fill", "none");


            var parseDate = d3.time.format("%d-%m-%Y");

            // Draw circles for every game/elo
            var points = vis.selectAll(".point")
                .data(history)
                .enter().append("svg:circle")
                .attr("cx", function (d, i) {
                    return xRange(d.date)
                })
                .attr("cy", function (d, i) {
                    return yRange(d.elo)
                })
                .attr("r", 3.5)
                .append("svg:title").text(function (d, i) {
                    return 'Elo: ' + d.elo + ' - ' + parseDate(d.date)
                });
        }
    }
};

$(jsFrontend.elo_rating.init);
