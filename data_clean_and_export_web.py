import streamlit as st
import pandas as pd

st.set_page_config(page_title="AI Data Cleaner", layout="wide")
st.title("🧼 ExData- Data Cleaner")

# Upload File
uploaded_file = st.file_uploader("📤 Upload your CSV or Excel file", type=['csv', 'xlsx'])

if uploaded_file:
    # Read file
    try:
        if uploaded_file.name.endswith('.csv'):
            df = pd.read_csv(uploaded_file)
        else:
            df = pd.read_excel(uploaded_file)
    except Exception as e:
        st.error(f"Error loading file: {e}")
        st.stop()

    st.subheader("🔍 Original Data Preview")
    st.dataframe(df.head())

    # Cleaning options
    st.subheader("🧹 Data Cleaning Options")

    if st.checkbox("Remove rows with all NULLs"):
        df = df.dropna(how='all')

    if st.checkbox("Remove duplicate rows"):
        df = df.drop_duplicates()

    if st.checkbox("Fill missing values"):
        fill_value = st.text_input("Fill missing values with:", value="0")
        df = df.fillna(fill_value)

    st.subheader("✅ Cleaned Data Preview")
    st.dataframe(df.head())

    # Export section
    st.subheader("📁 Export Cleaned Data")
    file_format = st.selectbox("Select export format", ["CSV", "Excel"])

    if file_format == "CSV":
        st.download_button(
            label="⬇️ Download CSV",
            data=df.to_csv(index=False),
            file_name="cleaned_data.csv",
            mime="text/csv"
        )
    else:
        from io import BytesIO
        output = BytesIO()
        df.to_excel(output, index=False, engine='openpyxl')
        st.download_button(
            label="⬇️ Download Excel",
            data=output.getvalue(),
            file_name="cleaned_data.xlsx",
            mime="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
        )
