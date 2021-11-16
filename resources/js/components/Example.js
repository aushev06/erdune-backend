import React from 'react';
import ReactDOM from 'react-dom';

function Example() {
    return (
        <div>wefwf</div>
    );
}

export default Example;

if (document.getElementById('react-form')) {
    ReactDOM.render(<Example />, document.getElementById('react-form'));
}
