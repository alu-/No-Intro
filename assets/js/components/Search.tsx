import React, { Component, ChangeEvent } from 'react';
import { debounce } from 'lodash';
import qs from 'qs';
import axios from 'axios';
import { GameList, GameDetails } from './GameList';

type SearchState = {
    query: String,
    games?: GameDetails[]
}

export class Search extends Component<{}, SearchState> {
    constructor(props: {}){
        super(props);

        this.state = {
            query: '',
            games: []
        }

        this.handleChange = this.handleChange.bind(this);
        this.search = debounce(this.search.bind(this), 500);
    }

    handleChange(event: ChangeEvent<HTMLInputElement>) {
        this.setState({query: event.target.value}, () => {
            if (this.state.query === "") {
                this.setState({games: []});
            } else {
                this.search();
            }
        });
    }

    search() {
        axios.post('/api/v1/search', qs.stringify({query: this.state.query}), {responseType: 'json' })
             .then(response => {
                 this.setState({games: response.data.games});
             })
             .catch(error => {
                 alert(error);
                 console.debug(error);
             });
    }

    render() {
        return (
            <div className="max-w-md mx-auto my-6">
                <div className="mt-1 relative rounded shadow">
                    <div className="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                        <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" className="focus:ring-gray-500 focus:border-gray-500 block w-full pl-10 sm:text-sm border-gray-300 rounded" id="game" placeholder="Search No-Intro for a game .." onChange={this.handleChange} autoComplete="off" autoFocus/>
                    <label htmlFor="game" className="sr-only">Search No-Intro for a game ..</label>
                </div>
                <GameList games={this.state.games} />
            </div>
        )
    }
}
