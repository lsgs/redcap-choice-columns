********************************************************************************
# REDCap External Module: Choice Columns

Luke Stevens, Murdoch Children's Research Institute https://www.mcri.edu.au

[https://github.com/lsgs/redcap-choice-columns](https://github.com/lsgs/redcap-choice-columns)
********************************************************************************
## Summary

Tag radio or checkbox fields with the action tag `@CHOICE-COLUMNS=?` to specify the number of columns the choices will be displayed in.

Notes:
* The tag is ignored for fields other than radio and checkbox.
* The tag is ignored for radio and checkbox fields that are part of a matrix.
* Enhanced radio buttons and checkboxes are supported.
* Specify an integer in the range 1-99 for the number of columns. Any other value will cause the tag to be ignored and the field display default behaviour.
* Piping into the action tag *is* supported, but only on page load. It is not dynamic within a page (i.e. is similar to other tags like `@HIDECHOICE` and `@IF`).

********************************************************************************
## Alignment
### Vertical vs. Horizontal
* Horizontal: Choices will be displayed in columns with choices listed **across then down**.
* Vertical: Choices will be displayed in columns with choices listed **down then across**.

Note: down then across filling for fields with vertical alignment means that the specified column count may not be fully filled.

### Left vs. Right
* Left: **Recommended.** Choices occupy the full width of the form container, with the field label above. 
* Right: Choices occupy space to the right of the field label. Usually unsuitable with higher column counts.

********************************************************************************
## Multi-Language Management
The module is designed to support re-rendering following language changes, including between left-to-right and right-to-left languages.

### LV Alignment
| L-T-R   |         |<|-|>|         |    R-T-L|
|---------|---------|-|-|-|---------|---------|
| Choice1 | Choice4 | | | | 4eciohC | 1eciohC |
| Choice2 | Choice5 | | | | 5eciohC | 2eciohC |
| Choice3 |         | | | |         | 3eciohC |

### LH Alignment
| L-T-R   |         |<|-|>|         |    R-T-L|
|---------|---------|-|-|-|---------|---------|
| Choice1 | Choice2 | | | | 2eciohC | 1eciohC |
| Choice3 | Choice4 | | | | 4eciohC | 3eciohC |
| Choice5 |         | | | |         | 5eciohC |

********************************************************************************
## Example and Screenshots
[https://redcap.link/em-choice-columns](https://redcap.link/em-choice-columns)

[![example0](https://redcap.mcri.edu.au/surveys/index.php?__file=UML4tP3DFSNiJMVVZtvTs4tLgwWShdUXCpf6cQg9TXmamoKruUcULNrDgWtAd3rLwbyak6yTP9XuE7UsgXwIpWje329F6524Jox4&__passthru=DataEntry%2Fimage_view.php&doc_id_hash=4307d542e5880069740a87abb8590ad22444b062&id=1785490)](https://redcap.mcri.edu.au/surveys/index.php?__file=UML4tP3DFSNiJMVVZtvTs4tLgwWShdUXCpf6cQg9TXmamoKruUcULNrDgWtAd3rLwbyak6yTP9XuE7UsgXwIpWje329F6524Jox4&__passthru=DataEntry%2Fimage_view.php&doc_id_hash=4307d542e5880069740a87abb8590ad22444b062&id=1785490)

[![example1](https://redcap.mcri.edu.au/surveys/index.php?__file=PFUvZLFhXBtxrveL7hLGQVKTtY7U6KnVPKfKiDJXFEfBJLwFxFDpWio6iJhjgEIj77N9Q9LA44txwGdD7P546GPhjkyB2yev3TMy&__passthru=DataEntry%2Fimage_view.php&doc_id_hash=e62d420d182644d2723d00d369b919fe6df04ce2&id=1785491)(https://redcap.mcri.edu.au/surveys/index.php?__file=PFUvZLFhXBtxrveL7hLGQVKTtY7U6KnVPKfKiDJXFEfBJLwFxFDpWio6iJhjgEIj77N9Q9LA44txwGdD7P546GPhjkyB2yev3TMy&__passthru=DataEntry%2Fimage_view.php&doc_id_hash=e62d420d182644d2723d00d369b919fe6df04ce2&id=1785491)

[![example2](https://redcap.mcri.edu.au/surveys/index.php?__file=IPN2oZe3s4SpAeHD7iZ8B2gHXowHuwaLRPVUDKePVvs3zyd9M3oJqPjuihwt4vnSQbyw5quGNobd2i3PE4bgYT9IchE5ABrzCWgt&__passthru=DataEntry%2Fimage_view.php&doc_id_hash=060cde0a2b13c9e82fcd1b0e4e4bb9750be22dd9&id=1785483)(https://redcap.mcri.edu.au/surveys/index.php?__file=IPN2oZe3s4SpAeHD7iZ8B2gHXowHuwaLRPVUDKePVvs3zyd9M3oJqPjuihwt4vnSQbyw5quGNobd2i3PE4bgYT9IchE5ABrzCWgt&__passthru=DataEntry%2Fimage_view.php&doc_id_hash=060cde0a2b13c9e82fcd1b0e4e4bb9750be22dd9&id=1785483)

[![example3](https://redcap.mcri.edu.au/surveys/index.php?__file=sLkmoTUzvhz7UTyExCAiSGGbR2SpdjjUze8kp3rwiGfUtJMt8iG87UwJDqjcaCzVE9I7RcyYvQ9HtiUTogkJAUteMnu9iCozPEdd&__passthru=DataEntry%2Fimage_view.php&doc_id_hash=5a3f9e1e51c18cb119e771f18669a23f14697024&id=1785484)(https://redcap.mcri.edu.au/surveys/index.php?__file=sLkmoTUzvhz7UTyExCAiSGGbR2SpdjjUze8kp3rwiGfUtJMt8iG87UwJDqjcaCzVE9I7RcyYvQ9HtiUTogkJAUteMnu9iCozPEdd&__passthru=DataEntry%2Fimage_view.php&doc_id_hash=5a3f9e1e51c18cb119e771f18669a23f14697024&id=1785484)

********************************************************************************