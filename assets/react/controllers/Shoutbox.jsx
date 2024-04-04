import React, {useEffect, useState} from 'react';

export default function ({mercure, sendMessageUrl}) {
    const [message, setMessage] = useState('');

    useEffect(() => {
        const es = new EventSource(mercure);
        es.onmessage = event => {
            console.log(JSON.parse(event.data));
        }

        return () => es.close();
    }, []);

    const handleSendMessage = () => {
        if (message === '') {
            return;
        }

        fetch(sendMessageUrl, {
            method: 'POST',
            body: JSON.stringify({content: message})
        })
            .then(() => setMessage(() => ('')));
    }

    const handleKeyDown = (event) => {
        if (event.key === 'Enter') {
            handleSendMessage();
        }
    }

    return <>
        <input
            type="text"
            value={message}
            onKeyDown={handleKeyDown}
            onInput={e => setMessage(e.target.value)}
        />
        <button onClick={handleSendMessage}>Send</button>
    </>;
}
