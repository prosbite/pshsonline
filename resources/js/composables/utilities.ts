export function useConstants() {
    const genders = ['male', 'female']
    return {
        genders
    }
}

export function ucWords(str: string) {
    return str.replace(/\b\w/g, (match) => match.toUpperCase());
}

export function middleInitials(str: string) {
    if (str === '') {
        return '';
    }
    return str.replace(/\b\w/g, (match) => match.toUpperCase()).slice(0, 1) + '.';
}

