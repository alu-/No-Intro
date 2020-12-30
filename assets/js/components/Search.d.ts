import { Component, ChangeEvent } from 'react';
import { GameDetails } from './GameList';
declare type SearchState = {
    query: String;
    games?: GameDetails[];
};
export declare class Search extends Component<{}, SearchState> {
    constructor(props: {});
    handleChange(event: ChangeEvent<HTMLInputElement>): void;
    search(): void;
    render(): JSX.Element;
}
export {};
