##African Flood and Drought Monitor (AFDM)
####Web interface for visualizing and downloading hydrologic data

This repository contains the client-side interface (Javascript) and server-side scripts (Python) for the [African Flood and Drought Monitor](http://stream.princeton.edu/AWCM/WEBPAGE/). The AFDM allows users to visualize and download hydrologic data for the African continent, including observed and forecasted streamflow and drought indices.

The project uses several open-source libraries, which are stored in the `jsscripts/` directory. These include [Twitter Bootstrap](http://getbootstrap.com/) for styling, [JQuery](http://jquery.com/) for event handling, [JQuery UI](http://jqueryui.com/) for interactive UI elements, [HighCharts](http://www.highcharts.com/) for plotting time series data, and [PolygonEdit](https://github.com/bostonlogic/polygonEdit) for selecting spatial data. The interface is built on top of the [Google Maps API](https://developers.google.com/maps/). 

Contents:
* `thing`: 
* `other_thing`: 



#####Language translation
* Add the translation to the .po file
* Use the following command to update the .mo file:
'''
msgfmt -cv -o filename.mo filename.po
'''
* Make sure the filename is in a place that the php can find it
