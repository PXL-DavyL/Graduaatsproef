// This store will offer util functions for the permissions passed through Inertia's props.

import { usePage } from "@inertiajs/vue3";

/*
    This function will check if the AUTHENTICATED user has the role passed as parameter.
*/
export const hasRole = (check_role) => {
    if(usePage().props.auth.user === undefined || usePage().props.auth.user === null){
        return false;
    }

    for(const role of usePage().props.auth.user.roles){
        if(role.name === check_role){
            return true;
        }
    }
    
    return false;
};

/*
    This function checks if a specific user has a specific role.
*/
export const doesUserHaveRole = (user_object, check_role) => {

    for(const role of user_object.roles){
        if(role.name === check_role){
            return true;
        }
    }

    return false;
};

/*
    This function checks if a user has a specific permission
*/
export const hasPermission = (check_permission) => {
    if(usePage().props.auth.user === undefined || usePage().props.auth.user === null){
        return false;
    }

    for(const permission of usePage().props.auth.user.permissions){
        if(permission.name === check_permission){
            return true;
        }
    }

    return false;
}