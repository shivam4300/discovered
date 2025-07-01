import React from 'react';
import { NavLink } from 'react-router-dom';
import { ROUTES } from '../Constants';
import Logo from './ui/Logo';

export default function Menu() {
	return (
		<nav className="menu">
			<Logo />
			<ul>
				<li><NavLink to={ROUTES.SEASON_PASS}><i className="fa-solid fa-ticket"></i><span>Season Pass</span></NavLink></li>
				<li><NavLink to={ROUTES.LOOTBOXES}><i className="fa-solid fa-gift"></i><span>Lootboxes</span></NavLink></li>
				<li><NavLink to={ROUTES.PLAYER_PROFILE}><i className="fa-solid fa-user"></i><span>Player Profile</span></NavLink></li>
				<li><NavLink to={ROUTES.PLAYER_INVENTORY}><i className="fa-solid fa-user"></i><span>Player Inventory</span></NavLink></li>
				<li><NavLink to={ROUTES.LEADERBOARD}><i className="fa-solid fa-user"></i><span>Leaderboard</span></NavLink></li>
			</ul>
		</nav>
	);
}
