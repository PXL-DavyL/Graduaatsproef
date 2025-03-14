// This formats the date to a readable format


export const formatDate = (date) => {
    return new Date(date).toLocaleDateString(
        "nl-BE"
    )
};