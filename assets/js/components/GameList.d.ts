import { Component } from 'react';
export declare type GameDetails = {
    title: String;
    aliases?: String;
};
declare type GameProps = {
    games?: GameDetails[];
};
export declare class GameList extends Component<GameProps> {
    constructor(props: GameProps);
    render(): JSX.Element;
}
export {};
