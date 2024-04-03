import React, {useEffect} from 'react';

export default function ({mercure}) {
    useEffect(() => {
        const es = new EventSource(mercure);
        es.onmessage = event => {
            console.log(JSON.parse(event.data));
        }
    }, []);

    return <div>Hello 3</div>;
}
