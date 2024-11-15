import 'dart:io';
import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:image_picker/image_picker.dart';

class ImageInputBox extends StatefulWidget {
  final double height;
  final double width;
  final double iconSize;
  final ValueChanged<List<File>> onImagesChanged; // Add this property

  const ImageInputBox({
    Key? key,
    this.height = 80,
    this.width = 80,
    this.iconSize = 35,
    required this.onImagesChanged, // Add this required parameter
  }) : super(key: key);

  @override
  _ImageInputBoxState createState() => _ImageInputBoxState();
}


class _ImageInputBoxState extends State<ImageInputBox> {
  List<File> _images = [];
  bool _isImagePickerActive = false; // Add this variable

  Future<void> _pickImages() async {
    if (_isImagePickerActive) {
      return; // Prevent opening the picker again while it's active
    }

    setState(() {
      _isImagePickerActive = true;
    });

    final imagePicker = ImagePicker();
    final pickedImages = await imagePicker.pickMultiImage(
      imageQuality: 80,
    );

    setState(() {
      _isImagePickerActive = false;
    });

    if (_images.length + pickedImages.length <= 3) {
      setState(() {
        _images.addAll(pickedImages.map((image) => File(image.path)));
      });
      widget.onImagesChanged(_images); // Notify parent about selected images
    } else {
      print('เลือกรูปภาพสูงสุดได้ไม่เกิน 3 รูปภาพ');
    }
  }

  void _removeImage(int index) {
    setState(() {
      _images.removeAt(index);
    });
    widget.onImagesChanged(_images);
  }


  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        GestureDetector(
          onTap: _pickImages,
          child: Container(
            height: widget.height,
            width: widget.width,
            decoration: BoxDecoration(
              borderRadius: BorderRadius.circular(Dimensions.radius15),
              color: Colors.grey[200],
            ),
            child: Icon(
              Icons.image,
              color: Colors.grey,
              size: widget.iconSize,
            ),
          ),
        ),
        SizedBox(width: Dimensions.width15),
        Expanded(
          child: SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            child: Row(
              children: _images.asMap().entries.map(
                (entry) {
                  final index = entry.key;
                  final image = entry.value;
                  return Padding(
                    padding: const EdgeInsets.only(right: 8.0),
                    child: Stack(
                      children: [
                        Image.file(
                          image,
                          width: 70,
                          height: 70,
                          fit: BoxFit.cover,
                        ),
                        Positioned(
                          top: 0,
                          right: 0,
                          child: GestureDetector(
                            onTap: () => _removeImage(index),
                            child: Container(
                              padding: EdgeInsets.all(2),
                              decoration: const BoxDecoration(
                                shape: BoxShape.circle,
                                color: Colors.white,
                              ),
                              child: const Icon(
                                Icons.close,
                                size: 16,
                                color: Colors.red,
                              ),
                            ),
                          ),
                        ),
                      ],
                    ),
                  );
                },
              ).toList(),
            ),
          ),
        ),
      ],
    );
  }
}