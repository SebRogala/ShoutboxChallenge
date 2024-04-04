import React, {useEffect, useState} from 'react';

export default function ({mercure, sendMessageUrl, initialMessages}) {
    const [message, setMessage] = useState('');
    const [messages, setMessages] = useState(initialMessages);

    useEffect(() => {
        const es = new EventSource(mercure);
        es.onmessage = event => {
            const msg = JSON.parse(event.data);
            setMessages(oldMessages => ([...oldMessages, msg]));
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
        <div className={'messages-container'}>
            {messages?.map((item) => (
                <div key={item.id}>
                    {item.userName}: {item.content}
                </div>
            ))}
        </div>
        <input
            type="text"
            value={message}
            onKeyDown={handleKeyDown}
            onInput={e => setMessage(e.target.value)}
        />
        <button onClick={handleSendMessage}>Send</button>
    </>;
}
