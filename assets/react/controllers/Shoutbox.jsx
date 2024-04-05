import React, {useEffect, useRef, useState} from 'react';
import Message from "../components/Shoutbox/Message";
import Inputs from "../components/Shoutbox/Inputs";

export default function ({mercure, sendMessageUrl, initialMessages, maxMessagesToShow}) {
    const [messages, setMessages] = useState(initialMessages);

    const messagesEndRef = useRef(null);

    const scrollToBottom = () => {
        messagesEndRef.current?.scrollIntoView({behavior: "smooth"})
    }

    useEffect(() => {
        scrollToBottom()
    }, [messages]);

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

    return <>
        <div className={'messages-container'}>
            {messages?.map((item) => (
                <Message message={item} key={item.id}></Message>
            ))}
            <div ref={messagesEndRef}/>
        </div>
        <div className={'inputs-container'}>
            <Inputs sendMessageUrl={sendMessageUrl}></Inputs>
        </div>
    </>;
}
