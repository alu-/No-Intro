import React, { Component } from 'react';

export class Footer extends Component {
    render() {
        return (
            <footer className="mt-12 text-gray-500">
                <div className="grid grid-cols-2 gap-4">
                    <div>
                        <a href="#" className="pr-6">Terms of Use</a>
                        <a href="#" className="pr-6">Contribute</a>
                        <a href="#">About</a>
                    </div>
                    <div className="text-right">
                        <p>No-Intro © 2020 alu</p>
                        <p>Game information provided by <a href="https://www.giantbomb.com">Giant Bomb</a></p>
                    </div>
                </div>
            </footer>
        )
    }
}

