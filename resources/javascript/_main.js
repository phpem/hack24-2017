// required core foundation files
import { foundation } from 'foundation-sites/js/foundation.core';
import 'foundation-sites/js/foundation.util.mediaQuery';

// we need to attach the function we force-exported in the config
// above to the jQuery object in use in this file
$.fn.foundation = foundation;

// ready to go
//$(document).ready(function() {
//    $(document).foundation();


//});

import React from 'react';
import ReactDOM from 'react-dom';

class SearchForm extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      topic: "foo",
      username: "bar"
    };

    this.handleInputChange = this.handleInputChange.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
  }

  handleInputChange(event) {
    const target = event.target;
    const value = target.type === 'checkbox' ? target.checked : target.value;
    const name = target.name;

    this.setState({
      [name]: value
    });
  }

  handleSubmit(event) {
    alert('A search was submitted: ' + this.state.topic + ' ' + this.state.username);
    event.preventDefault();
  }

  render() {
    return (
      <form onSubmit={this.handleSubmit}>
        <label>
          Topic:
          <input name="topic" type="text" value={this.state.topic} onChange={this.handleInputChange} />
        </label>
        <label>
          Username:
          <input name="username" type="text" value={this.state.username} onChange={this.handleInputChange} />
        </label>
        <input type="submit" value="Submit" />
      </form>
    );
  }
}

ReactDOM.render(
  <SearchForm />,
  document.getElementById('search')
);
