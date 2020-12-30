import React, { Component } from 'react';
import { Header } from '../components/Header'
import { Search } from '../components/Search'
import { Footer } from '../components/Footer'

export class Home extends Component {
    render() {
        return (
            <>
                <Header />
                <Search />
                <Footer />
            </>
        )
    }
}
