import React from 'react';
import {Anchorme} from "react-anchorme";
import "./Message.css";

export default function ({message}) {
    return <>
        <div className={'message-container'} key={message.id}>
            <div className={'message-container__sender'}>
                <div>{message.userName}</div>
                <div>{message.createdAt}</div>
            </div>
            <div className={'message-container__content'}>
                <Anchorme target="_blank">{message.content}</Anchorme>
            </div>
        </div>
    </>
}
