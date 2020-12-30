import React, { Component } from 'react';

export type GameDetails = {
    title: String,
    aliases?: String
}

type GameProps = {
    games?: GameDetails[]
}

export class GameList extends Component<GameProps> {
    constructor(props: GameProps){
        super(props);

        console.log("EWAT", props);
    }

    render() {
        return (
            <ol>
                {this.props.games && this.props.games.map((game, index) => <li key={index.toString()}>{game.name}</li>)}
            </ol>
        )
    }
}
