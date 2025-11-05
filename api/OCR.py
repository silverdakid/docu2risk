import ocrmypdf
import fitz
#########################

def make_pdf_readable(input_pdf, output_pdf) :
    try :
        ocrmypdf.ocr(input_pdf, output_pdf, deskew=True, language='eng')
    except Exception as e :
        print(e)

def read_pdf(input_pdf) : 
    doc = fitz.open(input_pdf)
    return doc