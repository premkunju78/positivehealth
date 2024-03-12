import Cookies from 'js-cookie';
import axios from '../libs/axios'

const TokenKey = 'Auth-Token';

export function getToken() {
    return Cookies.get(TokenKey);
}

export function setToken(token) {
    return Cookies.set(TokenKey, token);
}

export function removeToken() {
    return Cookies.remove(TokenKey);
}


/**
 * Return if user is logged in
 * This is completely up to you and how you want to store the token in your frontend application
 * e.g. If you are using cookies to store the application please update this function
 */
// eslint-disable-next-line arrow-body-style
export const isUserLoggedIn = () => {
    return Cookies.get(TokenKey) !== null && Cookies.get(TokenKey) !== undefined;
}


export const userData = async(token) => {
    try {

        const { data } = await axios.get('/user');
        return data;
    } catch (error) {
        return false;
    }

}