import PyPDF2

org_fileName = 'file.pdf'  # 分割したいファイルのファイル名
new_fileName = 'page' # 分割後のファイル名

def splitPDF(src_path, new_basepath):
    org_pdf = PyPDF2.PdfFileReader(src_path)
    for i in range(org_pdf.numPages):
        new_pdf = PyPDF2.PdfFileWriter()
        new_pdf.addPage(org_pdf.getPage(i))
        i = '{0:02}'.format(i)
        with open('{}_{}.pdf'.format(new_basepath, i), 'wb') as f:
            new_pdf.write(f)

splitPDF(org_fileName, new_fileName)