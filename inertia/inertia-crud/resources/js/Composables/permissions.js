// This store will offer util functions for the permissions passed through Inertia's props.

import { usePage } from "@inertiajs/vue3";

export const hasRole = (check_role) => {
    if(usePage().props.auth === undefined || usePage().props.auth === null){
        return false;
    }

    for(role of usePage().props.auth.user.roles){
        if(role.name === check_role){
            return true;
        }
    }
    
    return false;
};