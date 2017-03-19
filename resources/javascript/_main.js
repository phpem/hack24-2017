// required core foundation files
import { foundation } from 'foundation-sites/js/foundation.core';
import 'foundation-sites/js/foundation.util.mediaQuery';

// we need to attach the function we force-exported in the config
// above to the jQuery object in use in this file
$.fn.foundation = foundation;

// ready to go
$(document).ready(function() {
    $(document).foundation();
});

import ReactDOM from 'react-dom';
import SearchForm from './components/SearchForm';

ReactDOM.render(
  <SearchForm />,
  document.getElementById('search')
);
