import React, {useEffect, useState} from 'react';
import {Anchorme} from "react-anchorme";

export default function ({mercure, sendMessageUrl, initialMessages, maxMessagesToShow}) {
    const [message, setMessage] = useState('');
    const [messages, setMessages] = useState(initialMessages);

    useEffect(() => {
        const es = new EventSource(mercure);
        es.onmessage = event => {
            const msg = JSON.parse(event.data);
            const computeMessages = (oldMessages) => {
                if (oldMessages.length < maxMessagesToShow) {
                    return [...oldMessages, msg];
                }

                return [...oldMessages.slice(1), msg];
            }

            setMessages(oldMessages => (computeMessages(oldMessages)));
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
                    {item.userName}: <Anchorme target="_blank">{item.content}</Anchorme>
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
