
import streamlit as st
from datetime import datetime
import requests
import json
from callollama import callOLLAMA

st.set_page_config(
    page_title='chatbot'
    )

if  "message" not in st.session_state:
    st.session_state.message=[]
    st.session_state.message.append({
            "role" :"assistant",
            "content":"Hello, I am Doto Chatbot. How can i help you ?"
            }
        )

if  "is_typing" not in st.session_state:
    st.session_state.is_typing=False

st.title("ExData")
st.markdown("Hii There! I am Doto, Your new Chatbot Friend")

st.subheader("Chat here")

if "messages" not in st.session_state:
    st.session_state.messages = []

for message in st.session_state.messages:
    if message["role"]=="user":
        st.write("")
        st.info(message["content"]) #info used to highlight msg in blue
    else:
        st.success(message["content"]) #success is used to highlight msg in green
    
if st.session_state.is_typing:
    st.markdown("Doto is typing")
    st.warning("Typing.....")

st.markdown("----")
st.subheader("Your Message")

with st.form(key="chat_bot", clear_on_submit=True):
    # Your form content
    user_input = st.text_input("Enter your message",
    placeholder="Ask me anything....")
    send_button = st.form_submit_button("Send message", type="primary")

col1, col2= st.columns([1,1])

with col1:
    clear_button=st.button("clear chat")

if send_button and user_input.strip():
    st.session_state.messages.append(
        {
            "role":"user",
            "content":user_input.strip()
        }
    )
    st.session_state.is_typing=True
    st.rerun()
if st.session_state.is_typing:
    user_message=st.session_state.messages[-1]["content"]
    bot_response=callOLLAMA(user_message)
    st.session_state.messages.append({
        "role":"assistant",
        "content":bot_response
    }
    )
    st.session_state.is_typing=False
    st.rerun()
