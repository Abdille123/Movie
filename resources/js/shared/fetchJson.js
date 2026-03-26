// Make JSON requests easier to reuse across the app.
export async function fetchJson(url, options = {}) {
    const { headers = {}, ...rest } = options;
    const response = await fetch(url, {
        headers: {
            Accept: 'application/json',
            ...headers,
        },
        ...rest,
    });

    return {
        response,
        data: await response.json(),
    };
}
