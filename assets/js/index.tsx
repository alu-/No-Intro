import '../styles/app.scss';
import './bootstrap'; // start the Stimulus application

import React from 'react';
import ReactDOM from 'react-dom';

import { BrowserRouter as Router } from 'react-router-dom';
import { Home } from './pages/Home';


const App = () => (
    <Router><Home /></Router>
)

ReactDOM.render(
    <App />,
    document.getElementById('root')
);
