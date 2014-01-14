##African Flood and Drought Monitor (AFDM)
####Web interface for visualizing and downloading hydrologic data

This repository contains the client-side interface (Javascript) and server-side scripts (PHP, Python) for the [African Flood and Drought Monitor](http://stream.princeton.edu/AWCM/WEBPAGE/). The AFDM allows users to visualize and download hydrologic data for the African continent, including observed and forecasted streamflow and drought indices.

The project uses several open-source libraries, which are stored in the `jsscripts/` directory. These include [Twitter Bootstrap](http://getbootstrap.com/) for styling, [JQuery](http://jquery.com/) for event handling, [JQuery UI](http://jqueryui.com/) for interactive UI elements, [HighCharts](http://www.highcharts.com/) for plotting time series data, and [PolygonEdit](https://github.com/bostonlogic/polygonEdit) for selecting spatial data. The interface is built on top of the [Google Maps API](https://developers.google.com/maps/). 

Contents:
* `index.php, interface.php`, and `sidebar.php`: PHP scripts (server-side) to generate the HTML content of the interface. The PHP scripts read the list of user-selected datasets from a `settings.xml` file
* `css/`: Stylesheets. Contains the Bootstrap defaults along with custom styles for the interface
* `icons/`: Images used on the website
* `img/`: Small icon sets from [Glyphicons - Halflings](http://glyphicons.com/).
* `jsscripts/`: Client-side Javascript for interactive event handling. In addition to the open source libraries listed above, this directory contains the following custom scripts:
  * `AnimationPrep.js`: Prepare the arrays of image URLs that will be requested each time the user updates the time period or the dataset selected
  * `data_extraction.js`: Change event listener types when user switches between Animation, Point Data, and Spatial Data modes. Also defines events for user selection of point and spatial data.
  * `ImageOverlay.js`: Definition of image overlays and associated functions, as per the Google Maps API.
  * `MainFunctions.js`: Event handlers primarily for switching between modes using the sidebar. (Monitor/Forecast, Animation/Point Data/Spatial Data, etc.). 
  * `MiscFunctions.js`: Additional functions used by the interface.
  * `StaticImages.js`: Update static image URLs depending on user selection of dataset and time period.
  * `timestep.js`: Event handlers for timestep selection on the sidebar
* `languages/`: Scripts and language files for internationalization. Currently the interface supports five languages: English, French, Spanish, Arabic, and Chinese. To translate:
  * Add the translation to the .po file
  * Use the following command to update the .mo file:
  ```
  msgfmt -cv -o filename.mo filename.po
  ```
  * Make sure the filename is in a place that the php can find it
* `Resources/`: PDF documents including background, glossary, and tutorial
* `scripts/`: Server-side PHP and Python scripts for handling user requests for point data (time series) and spatial data (maps). 
